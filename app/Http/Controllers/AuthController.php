<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected function redirectAfterLogin(Request $request, string $fallback)
    {
        $intended = $request->session()->pull('url.intended');

        if (is_string($intended) && $this->isSafeIntendedUrl($request, $intended)) {
            return redirect()->to($intended);
        }

        return redirect()->to($fallback);
    }

    protected function isSafeIntendedUrl(Request $request, string $intendedUrl): bool
    {
        $normalizedPath = (string) parse_url($intendedUrl, PHP_URL_PATH);

        if ($normalizedPath === '') {
            return false;
        }

        if (Str::startsWith($normalizedPath, '/api/')) {
            return false;
        }

        $host = parse_url($intendedUrl, PHP_URL_HOST);

        return $host === null || $host === $request->getHost();
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Setelah registrasi, arahkan ke halaman login
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $login = $credentials['username'];

        // Cek apakah user terdaftar. Terima username atau email agar akun lama tetap bisa masuk.
        $user = User::where('username', $login)
            ->orWhere('email', $login)
            ->first();
        if (! $user) {
            return back()->withErrors([
                'username' => 'Akun tidak ditemukan.',
            ])->onlyInput('username', 'remember');
        }

        // Cek apakah user aktif
        if (! $user->is_active) {
            return back()->withErrors([
                'username' => 'Akun Anda tidak aktif. Hubungi administrator.',
            ])->onlyInput('username', 'remember');
        }

        $remember = $request->has('remember');

        if (Auth::attempt([
            'username' => $user->username,
            'password' => $credentials['password'],
        ], $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $access = $user->module_access ?? ['surat'];
            $loginSource = $request->input('login_source', 'portal');

            // 1. Logic Login dari Pintu 'ASET'
            if ($loginSource === 'aset') {
                if (in_array('aset', $access)) {
                    // Punya akses, masuk ke Aset Dashboard
                    return $this->redirectAfterLogin($request, route('aset.dashboard'));
                } else {
                    // Login valid tapi TIDAK PUNYA AKSES ASET
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return back()->with('error', 'Akun Anda tidak memiliki akses ke Sistem Aset.');
                }
            }

            // 2. Logic Login dari Pintu 'SDM'
            if ($loginSource === 'sdm') {
                if (in_array('sdm', $access)) {
                     // Punya akses, masuk ke SDM Dashboard
                     return $this->redirectAfterLogin($request, route('sdm.dashboard'));
                } else {
                     // Login valid tapi TIDAK PUNYA AKSES SDM
                     Auth::logout();
                     $request->session()->invalidate();
                     $request->session()->regenerateToken();
                     return back()->with('error', 'Akun Anda tidak memiliki akses ke Sistem SDM.');
                }
            }

            // 3. Logic Login dari Pintu 'KEUANGAN'
            if ($loginSource === 'keuangan') {
                if (in_array('keuangan', $access)) {
                     return $this->redirectAfterLogin($request, route('keuangan.dashboard'));
                } else {
                     Auth::logout();
                     $request->session()->invalidate();
                     $request->session()->regenerateToken();
                     return back()->with('error', 'Akun Anda tidak memiliki akses ke Sistem Keuangan.');
                }
            }

            // 4. Logic Login dari Pintu 'PEGAWAI'
            if ($loginSource === 'pegawai') {
                if (array_intersect(['pegawai', 'kepegawaian', 'sdm'], $access)) {
                     return $this->redirectAfterLogin($request, route('kepegawaian.dashboard'));
                } else {
                     Auth::logout();
                     $request->session()->invalidate();
                     $request->session()->regenerateToken();
                     return back()->with('error', 'Akun Anda tidak memiliki akses ke Portal Pegawai.');
                }
            }

            // 5. Logic Login dari Pintu 'KEPEGAWAIAN'
            if ($loginSource === 'kepegawaian') {
                if (array_intersect(['sdm', 'kepegawaian', 'pegawai'], $access)) {
                    if ($user->must_change_password ?? false) {
                        return redirect()->route('kepegawaian.password.change');
                    }
                    return $this->redirectAfterLogin($request, route('kepegawaian.dashboard'));
                } else {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return back()->with('error', 'Akun Anda tidak memiliki akses ke Sistem Kepegawaian.');
                }
            }

            // 6. Logic Login dari Pintu 'PORTAL/SURAT' (Default)
            // Cek apakah punya akses surat (default assume yes for legacy, or strict if needed)
            // Untuk user Aset-Only, SDM-Only, Keuangan-Only, atau Pegawai-Only, kita blokir di sini
            
            // Misal: User Aset Only ('access' => ['aset']) mencoba login di Portal
            // Kita bisa cek apakah dia punya 'surat'. Kalau tidak -> blokir.
            if (!in_array('surat', $access) && !in_array('*', $access)) { // * for superuser assumption if any
                 // Jika user cuma punya akses aset, dia tidak boleh login di portal utama
                 // Kecuali kita mau Portal jadi General Landing Page. 
                 // Tapi request user: "Login akun selain admin login aset malah masuk surat" -> implies separation.
                 
                 // Namun, halaman Portal (welcome) itu public. Login Portal usually means Login Surat.
                 // Mari kita asumsikan Login Portal = Login Surat.
                 
                 // Cek Strict:
                 Auth::logout();
                 $request->session()->invalidate();
                 return back()->with('error', 'Akun Anda khusus modul lain. Silakan login di halaman yang sesuai.');
            }

            // Punya akses surat/portal
            return $this->redirectAfterLogin($request, route('dashboard'));
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username', 'remember');
    }

    public function logout(Request $request)
    {
        $source = $request->input('source') ?? $request->query('source');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($source === 'sdm') {
            return redirect()->route('sdm.login');
        } elseif ($source === 'aset') {
            return redirect()->route('aset.login');
        } elseif ($source === 'keuangan') {
            return redirect()->route('keuangan.login');
        } elseif ($source === 'pegawai') {
            return redirect()->route('kepegawaian.login');
        } elseif ($source === 'kepegawaian') {
            return redirect()->route('kepegawaian.login');
        }

        return redirect('/login');
    }
}

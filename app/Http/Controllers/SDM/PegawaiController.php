<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmPegawai;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = SdmPegawai::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $pegawais = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('sdm.pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('sdm.pegawai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nip' => 'required|unique:sdm_pegawais',
            'role' => 'required',
            'join_date' => 'required|date',
            'email' => 'nullable|email|unique:sdm_pegawais',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->has('create_account') && !$request->email) {
            return back()->withErrors(['email' => 'Email wajib diisi jika ingin membuat akun login otomatis.'])->withInput();
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pegawai-photos', 'public');
        }

        $pegawai = SdmPegawai::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'nidn' => $request->nidn,
            'role' => $request->role,
            'join_date' => $request->join_date,
            'status' => $request->status ?? 'active',
            'status_kepegawaian' => $request->status_kerja ?? 'Tetap',
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_lengkap' => $request->alamat_lengkap,
            'phone' => $request->phone,
            'email' => $request->email,
            'foto' => $fotoPath,
            'gaji_pokok' => $request->gaji_pokok ?? 0,
            'tunjangan' => $request->tunjangan ?? 0,
        ]);

        // Logic Buat Akun Login Otomatis
        if ($request->has('create_account')) {
            $request->validate([
                'username' => 'required|unique:users',
                'password' => 'required|confirmed|min:6',
            ]);

            \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email, 
                'username' => $request->username,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'role' => 'staff', 
                'module_access' => ['pegawai'], 
                'is_active' => true, 
            ]);
            
            // Link user to employee
            $user = \App\Models\User::where('username', $request->username)->first();
            if($user) {
                $pegawai->update(['user_id' => $user->id]);
            }
        }

        return redirect()->route('sdm.pegawai.index')->with('success', 'Pegawai baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pegawai = SdmPegawai::findOrFail($id);
        return view('sdm.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = SdmPegawai::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'nip' => 'required|unique:sdm_pegawais,nip,'.$id,
            'role' => 'required',
            'join_date' => 'required|date',
            'email' => 'nullable|email|unique:sdm_pegawais,email,'.$id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'nip' => $request->nip,
            'nidn' => $request->nidn,
            'role' => $request->role,
            'join_date' => $request->join_date,
            'status' => $request->status,
            'status_kepegawaian' => $request->status_kerja ?? $pegawai->status_kepegawaian,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_lengkap' => $request->alamat_lengkap,
            'phone' => $request->phone,
            'email' => $request->email,
            'gaji_pokok' => $request->gaji_pokok ?? 0,
            'tunjangan' => $request->tunjangan ?? 0,
        ];

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($pegawai->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($pegawai->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($pegawai->foto);
            }
            $data['foto'] = $request->file('foto')->store('pegawai-photos', 'public');
        }

        $pegawai->update($data);

        return redirect()->route('sdm.pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function show($id)
    {
        $pegawai = SdmPegawai::with(['pendidikans', 'keluargas', 'riwayatJabatans.masterJabatan', 'riwayatPangkats'])->findOrFail($id);
        return view('sdm.pegawai.show', compact('pegawai'));
    }

    public function destroy($id)
    {
        $pegawai = SdmPegawai::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('sdm.pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }
    public function export()
    {
        $fileName = 'data-pegawai-' . date('Y-m-d') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Nama', 'NIP', 'NIDN', 'Role', 'Status Kepegawaian', 'Status', 'Tanggal Masuk', 'Jenis Kelamin', 'Email', 'Telepon', 'Alamat');

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel to recognize UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Use semicolon as delimiter for Excel friendliness in Indonesia
            fputcsv($file, $columns, ';');

            $pegawais = SdmPegawai::all();

            foreach ($pegawais as $pegawai) {
                // Sanitize fields to prevent formulas or line breaks breaking CSV
                $row = [
                    'Nama' => $pegawai->name,
                    'NIP' => $pegawai->nip,
                    'NIDN' => $pegawai->nidn,
                    'Role' => $pegawai->role,
                    'Status Kepegawaian' => $pegawai->status_kepegawaian,
                    'Status' => $pegawai->status,
                    'Tanggal Masuk' => $pegawai->join_date,
                    'Jenis Kelamin' => $pegawai->jenis_kelamin,
                    'Email' => $pegawai->email,
                    'Telepon' => $pegawai->phone,
                    'Alamat' => preg_replace("/\r\n|\r|\n/", " ", $pegawai->alamat_lengkap) // Remove newlines
                ];

                fputcsv($file, array_values($row), ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $filename = $file->getPathname();

        if (!file_exists($filename) || !is_readable($filename)) {
            return back()->with('error', 'File tidak dapat dibaca.');
        }

        $header = null;
        $data = array();
        
        // Define expected headers mapping (CSV Header => DB Column)
        $map = [
            'Nama' => 'name',
            'NIP' => 'nip',
            'NIDN' => 'nidn',
            'Role' => 'role',
            'Status Kepegawaian' => 'status_kepegawaian',
            'Status' => 'status',
            'Tanggal Masuk' => 'join_date',
            'Jenis Kelamin' => 'jenis_kelamin',
            'Email' => 'email',
            'Telepon' => 'phone',
            'Alamat' => 'alamat_lengkap'
        ];

        // Attempt to detect delimiter (Comma or Semicolon)
        $delimiter = ',';
        $handle = fopen($filename, 'r');
        if ($handle) {
            $firstLine = fgets($handle);
            if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
                $delimiter = ';';
            }
            rewind($handle);
            
            // Skip BOM if present
            $bom = fread($handle, 3);
            if ($bom !== chr(0xEF).chr(0xBB).chr(0xBF)) {
                rewind($handle);
            }

            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                    // Trim headers
                    $header = array_map('trim', $header);
                    
                    // Validate Headers
                    $requiredHeaders = ['Nama', 'NIP']; // Minimal required
                    $missing = array_diff($requiredHeaders, $header);
                    
                    if (!empty($missing)) {
                        fclose($handle);
                        return back()->with('error', 'Format file tidak sesuai! Kolom berikut tidak ditemukan: ' . implode(', ', $missing) . '. Silakan Export data terlebih dahulu untuk mendapatkan template yang benar.');
                    }
                } else {
                    if (count($header) != count($row)) {
                        continue;
                    }
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        if (empty($data)) {
            return back()->with('error', 'File kosong atau tidak ada data yang terbaca.');
        }

        $count = 0;
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($data as $row) {
                // Ensure required fields
                if (empty($row['Nama']) || empty($row['NIP'])) continue;

                SdmPegawai::updateOrCreate(
                    ['nip' => $row['NIP']], // Use NIP as unique key
                    [
                        'name' => $row['Nama'],
                        'nidn' => $row['NIDN'] ?? null,
                        'role' => $row['Role'] ?? 'staff',
                        'status_kepegawaian' => $row['Status Kepegawaian'] ?? 'Tetap',
                        'status' => $row['Status'] ?? 'active',
                        'join_date' => (!empty($row['Tanggal Masuk']) && strtotime($row['Tanggal Masuk'])) ? date('Y-m-d', strtotime($row['Tanggal Masuk'])) : now(),
                        'jenis_kelamin' => $row['Jenis Kelamin'] ?? null,
                        'email' => $row['Email'] ?? null,
                        'phone' => $row['Telepon'] ?? null,
                        'alamat_lengkap' => $row['Alamat'] ?? null,
                    ]
                );
                $count++;
            }
            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('sdm.pegawai.index')->with('success', "$count data pegawai berhasil diimport.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat import data: ' . $e->getMessage());
        }
    }
}

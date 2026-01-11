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
        ]);

        if ($request->has('create_account') && !$request->email) {
            return back()->withErrors(['email' => 'Email wajib diisi jika ingin membuat akun login otomatis.'])->withInput();
        }

        SdmPegawai::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'role' => $request->role,
            'join_date' => $request->join_date,
            'status' => 'active',
            'phone' => $request->phone,
            'email' => $request->email,
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
                'email' => $request->email, // Email harus ada jika ingin auto-connect
                'username' => $request->username,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'role' => 'staff', // Default role for Pegawai
                'module_access' => ['pegawai'], // Grant access to Pegawai Portal
                'is_active' => true,
            ]);
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
        ]);

        $pegawai->update([
            'name' => $request->name,
            'nip' => $request->nip,
            'role' => $request->role,
            'join_date' => $request->join_date,
            'status' => $request->status ?? $pegawai->status,
            'phone' => $request->phone,
            'email' => $request->email,
            'gaji_pokok' => $request->gaji_pokok ?? 0,
            'tunjangan' => $request->tunjangan ?? 0,
        ]);

        return redirect()->route('sdm.pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pegawai = SdmPegawai::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('sdm.pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}

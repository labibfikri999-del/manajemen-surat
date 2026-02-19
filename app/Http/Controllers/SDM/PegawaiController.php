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

    public function downloadTemplate()
    {
        $fileName = 'template-import-pegawai.xlsx';

        // Headers
        $header = [
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Nama</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>NIP</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Role</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Status Kepegawaian</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Status Aktif</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Tanggal Masuk</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Jenis Kelamin</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Email</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Telepon</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Alamat</b></style>'
        ];

        // Example Data
        $example = [
            'John Doe',
            '1234567890',
            'staff',
            'Tetap',
            'active',
            date('Y-m-d'),
            'L',
            'john@example.com',
            '081234567890',
            'Jl. Contoh No. 1'
        ];

        $data = [$header, $example];

        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($data);
        $xlsx->downloadAs($fileName);
        exit;
    }

    public function export()
    {
        $pegawais = SdmPegawai::all();
        $fileName = 'data-pegawai-' . date('Y-m-d') . '.xlsx';

        // Headers
        $header = [
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Nama</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>NIP</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Jabatan</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Status Kepegawaian</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Status Aktif</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Tanggal Masuk</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Jenis Kelamin</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Email</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Telepon</b></style>',
            '<style bgcolor="#4f46e5" color="#ffffff"><b>Alamat</b></style>'
        ];

        $data = [$header];

        foreach ($pegawais as $pegawai) {
            $data[] = [
                $pegawai->name,
                (string)$pegawai->nip, // Force string
                $pegawai->role,
                $pegawai->status_kepegawaian,
                $pegawai->status,
                $pegawai->join_date,
                $pegawai->jenis_kelamin,
                $pegawai->email,
                (string)$pegawai->phone,
                $pegawai->alamat_lengkap
            ];
        }

        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($data);
        $xlsx->downloadAs($fileName);
        exit;
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');
        $filename = $file->getPathname();

        if (!file_exists($filename) || !is_readable($filename)) {
            return back()->with('error', 'File tidak dapat dibaca.');
        }

        $header = null;
        $data = [];

        // 1. Try SimpleXLSX for Excel files
        if ($xlsx = \Shuchkin\SimpleXLSX::parse($filename)) {
            $rows = $xlsx->rows();
            
            if (count($rows) > 0) {
                // Get Header from first row
                $header = array_map('trim', $rows[0]);
                
                // Get Data
                for ($i = 1; $i < count($rows); $i++) {
                    if (count($header) === count($rows[$i])) {
                        $data[] = array_combine($header, $rows[$i]);
                    }
                }
            }
        } 
        // 2. Fallback to CSV
        else {
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
                        $header = array_map('trim', $row);
                    } else {
                        if (count($header) != count($row)) {
                             // Handle case where row length doesn't match header
                             // Basic fix: Pad or Trim
                             if (count($row) < count($header)) {
                                 $row = array_pad($row, count($header), null);
                             } else {
                                 $row = array_slice($row, 0, count($header));
                             }
                        }
                        $data[] = array_combine($header, $row);
                    }
                }
                fclose($handle);
            }
        }

        if (empty($data)) {
            return back()->with('error', 'File kosong atau format tidak dikenali. Harap gunakan file .xlsx hasil export atau .csv.');
        }

        // Validate Headers
        // Define map for normalization
        $map = [
            'Nama' => 'Nama', 
            '<b>Nama</b>' => 'Nama', // Handle potential HTML tag in header from old template
            'NIP' => 'NIP',
            '<b>NIP</b>' => 'NIP'
        ];
        
        // Clean headers from data if they contain HTML tags (SimpleXLSX might return raw strings)
        $cleanData = [];
        foreach ($data as $inputRow) {
            $cleanRow = [];
            foreach ($inputRow as $key => $value) {
                $cleanKey = strip_tags($key);
                $cleanRow[$cleanKey] = $value;
            }
            $cleanData[] = $cleanRow;
        }

        // Validate Headers Check
        $firstRowKeys = array_keys($cleanData[0] ?? []);
        // Check minimal required column
        if (!in_array('Nama', $firstRowKeys) || !in_array('NIP', $firstRowKeys)) {
             return back()->with('error', 'Format file tidak sesuai! Kolom "Nama" dan "NIP" wajib ada. Silakan gunakan template yang disediakan.');
        }

        $successCount = 0;
        $errors = [];

        foreach ($cleanData as $index => $row) {
            $rowNumber = $index + 2; // +2 because index starts at 0 and header is row 1
            
            // Skip empty rows
            if (empty($row['Nama']) && empty($row['NIP'])) continue;

            // Normalize Data
            $input = [
                'name' => $row['Nama'] ?? null,
                'nip' => $row['NIP'] ?? null,
                'role' => isset($row['Role']) ? strtolower($row['Role']) : 'staff',
                'join_date' => isset($row['Tanggal Masuk']) ? date('Y-m-d', strtotime($row['Tanggal Masuk'])) : date('Y-m-d'),
                'status' => isset($row['Status Aktif']) ? strtolower($row['Status Aktif']) : 'active',
                'status_kepegawaian' => $row['Status Kepegawaian'] ?? 'Tetap',
                'jenis_kelamin' => isset($row['Jenis Kelamin']) ? strtoupper($row['Jenis Kelamin']) : 'L',
                'email' => $row['Email'] ?? null,
                'phone' => $row['Telepon'] ?? null,
                'alamat_lengkap' => $row['Alamat'] ?? null,
            ];

            // Manual Validation for this row
            $validator = \Illuminate\Support\Facades\Validator::make($input, [
                'name' => 'required',
                'nip' => 'required', // We handle uniqueness check manually for updateOrCreate logic or strict insert
                // 'email' => 'nullable|email',
            ]);

            if ($validator->fails()) {
                $errors[] = "Baris {$rowNumber}: " . implode(', ', $validator->errors()->all());
                continue;
            }

            try {
                // Update or Create based on NIP
                $pegawai = SdmPegawai::updateOrCreate(
                    ['nip' => $input['nip']],
                    [
                        'name' => $input['name'],
                        'role' => $input['role'],
                        'join_date' => $input['join_date'],
                        'status' => $input['status'],
                        'status_kepegawaian' => $input['status_kepegawaian'],
                        'jenis_kelamin' => $input['jenis_kelamin'],
                        'email' => $input['email'],
                        'phone' => $input['phone'],
                        'alamat_lengkap' => $input['alamat_lengkap'],
                    ]
                );
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowNumber}: Gagal menyimpan data ({$input['name']}) - " . $e->getMessage();
            }
        }

        if ($successCount > 0) {
            $message = "Berhasil memproses {$successCount} data pegawai.";
            if (count($errors) > 0) {
                return back()->with('warning', $message)->with('import_errors', $errors);
            }
            return back()->with('success', $message);
        } else {
            return back()->with('error', 'Tidak ada data yang berhasil diimport.')->with('import_errors', $errors);
        }
    }
}

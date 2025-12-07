<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Masuk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .header-info {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table thead {
            background-color: #f3f4f6;
            border-bottom: 2px solid #999;
        }
        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        .date {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Laporan Surat Masuk</h1>
    <div class="header-info">
        <span class="date">Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 20%">Instansi</th>
                <th style="width: 25%">Judul / Deskripsi</th>
                <th style="width: 15%">Status</th>
                <th style="width: 25%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dokumens as $dok)
                <tr>
                    <td>{{ $dok->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $dok->instansi->nama ?? 'Umum' }}</td>
                    <td>
                        <strong>{{ $dok->judul }}</strong><br>
                        <span style="color: #666; font-size: 10px;">{{ \Illuminate\Support\Str::limit($dok->deskripsi, 50) }}</span>
                    </td>
                    <td>{{ ucfirst($dok->status) }}</td>
                    <td>
                        @if($dok->status == 'disetujui')
                            Valid: {{ $dok->tanggal_validasi ? $dok->tanggal_validasi->format('d/m/Y') : '-' }}
                        @elseif($dok->status == 'selesai')
                            Selesai: {{ $dok->tanggal_selesai ? $dok->tanggal_selesai->format('d/m/Y') : '-' }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada dokumen ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total: {{ count($dokumens) }} dokumen</p>
        <p>Dokumen ini dicetak secara otomatis dari sistem Manajemen Surat Masuk</p>
    </div>
</body>
</html>

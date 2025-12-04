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
                <th style="width: 10%">No Surat</th>
                <th style="width: 12%">Tanggal</th>
                <th style="width: 20%">Pengirim</th>
                <th style="width: 35%">Perihal</th>
                <th style="width: 23%">Klasifikasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($surats as $surat)
                <tr>
                    <td>{{ $surat->no_surat }}</td>
                    <td>{{ \Carbon\Carbon::parse($surat->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $surat->pengirim }}</td>
                    <td>{{ $surat->perihal }}</td>
                    <td>{{ $surat->klasifikasi->nama ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total: {{ count($surats) }} surat</p>
        <p>Dokumen ini dicetak secara otomatis dari sistem Manajemen Surat Masuk</p>
    </div>
</body>
</html>

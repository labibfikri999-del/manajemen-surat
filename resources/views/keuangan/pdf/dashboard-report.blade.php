<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #f59e0b; padding-bottom: 20px; }
        .header h1 { margin: 0; color: #1f2937; }
        .header p { margin: 5px 0 0; color: #6b7280; font-size: 14px; }
        
        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 15px; color: #f59e0b; text-transform: uppercase; border-left: 4px solid #f59e0b; padding-left: 10px; }
        
        .big-stats { display: table; width: 100%; margin-bottom: 30px; }
        .stat-box { display: table-cell; width: 33%; padding: 15px; background: #f9fafb; border: 1px solid #e5e7eb; text-align: center; }
        .stat-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; }
        .stat-value { font-size: 18px; font-weight: bold; margin-top: 5px; }
        .text-green { color: #10b981; }
        .text-red { color: #ef4444; }
        .text-amber { color: #f59e0b; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
        th { text-align: left; padding: 10px; background-color: #fffbeb; color: #92400e; border-bottom: 1px solid #fcd34d; font-weight: bold; }
        td { padding: 8px 10px; border-bottom: 1px solid #f3f4f6; }
        tr:nth-child(even) { background-color: #f9fafb; }

        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Ringkasan Keuangan</h1>
        <p>YARSI NTB - Dicetak pada {{ date('d F Y, H:i') }}</p>
    </div>

    <!-- Stats Overview -->
    <div class="big-stats">
        <div class="stat-box">
            <div class="stat-label">Total Pemasukan</div>
            <div class="stat-value text-green">{{ $stats['pemasukan'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Pengeluaran</div>
            <div class="stat-value text-red">{{ $stats['pengeluaran'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Laba Bersih</div>
            <div class="stat-value text-amber">{{ $stats['laba'] }}</div>
        </div>
    </div>

    <!-- Budgets -->
    <div class="section-title">Realisasi Anggaran</div>
    <table>
        <thead>
            <tr>
                <th>Departemen</th>
                <th>Terpakai (Rp)</th>
                <th>Limit</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budgets as $budget)
            <tr>
                <td>{{ $budget->dept }}</td>
                <td>{{ $budget->used_amount_formatted }}</td>
                <td>{{ $budget->limit }}</td>
                <td>
                    <span style="color: {{ $budget->used > 80 ? '#ef4444' : '#10b981' }}">
                        {{ $budget->used }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Claims -->
    <div class="section-title" style="margin-top: 30px;">Status Klaim Asuransi</div>
    <table>
        <thead>
            <tr>
                <th>Provider</th>
                <th>Jumlah Klaim</th>
                <th>Status</th>
                <th>Diajukan Pada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($claims as $claim)
            <tr>
                <td>{{ $claim->provider }}</td>
                <td>Rp {{ number_format($claim->amount, 0, ',', '.') }}</td>
                <td>{{ $claim->status }}</td>
                <td>{{ \Carbon\Carbon::parse($claim->submitted_at)->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh Sistem Manajemen Keuangan YARSI NTB.</p>
    </div>
</body>
</html>

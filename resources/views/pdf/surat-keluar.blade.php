<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $surat->nomor_surat }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #333;
            margin: 2cm;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 10pt;
        }
        .content {
            margin-top: 20px;
        }
        .nomor-tanggal {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .tujuan {
            margin-bottom: 20px;
        }
        .perihal {
            margin-bottom: 20px;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .sign-area {
            display: inline-block;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $instansi_name ?? 'SISTEM MANAJEMEN SURAT' }}</h1>
        <p>Alamat: Jl. Contoh No. 123, Kota, Provinsi</p>
    </div>

    <table width="100%" style="margin-bottom: 20px;">
        <tr>
            <td width="60%">
                Nomor: {{ $surat->nomor_surat }}<br>
                Lampiran: -<br>
                Hal: <b>{{ $surat->perihal }}</b>
            </td>
            <td width="40%" style="text-align: right; vertical-align: top;">
                Tanggal: {{ date('d F Y', strtotime($surat->tanggal_keluar)) }}
            </td>
        </tr>
    </table>

    <div class="tujuan">
        Yth. <b>{{ $surat->tujuan }}</b><br>
        di Tempat
    </div>

    <div class="content">
        {!! $surat->konten !!}
    </div>

    <div class="footer">
        <div class="sign-area">
            <p>Hormat Kami,</p>
            <br><br><br>
            <p><b>_______________________</b></p>
        </div>
    </div>
</body>
</html>

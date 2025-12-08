<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; }
        .header { background-color: #10b981; color: white; padding: 15px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; }
        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #10b981; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Dokumen Masuk Baru</h2>
        </div>
        <div class="content">
            <p>Yth. Admin Instansi,</p>
            <p>Anda telah menerima dokumen baru dari Staff Yayasan Bersih dengan rincian sebagai berikut:</p>
            
            <table style="width: 100%; margin-bottom: 15px;">
                <tr>
                    <td style="font-weight: bold; width: 120px;">Judul:</td>
                    <td>{{ $dokumen->judul }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Nomor:</td>
                    <td>{{ $dokumen->nomor_dokumen }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Jenis:</td>
                    <td>{{ ucwords(str_replace('_', ' ', $dokumen->jenis_dokumen)) }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Tanggal:</td>
                    <td>{{ $dokumen->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @if($dokumen->deskripsi)
                <tr>
                    <td style="font-weight: bold;">Deskripsi:</td>
                    <td>{{ $dokumen->deskripsi }}</td>
                </tr>
                @endif
            </table>

            <p>Dokumen telah dilampirkan dalam email ini. Anda juga dapat mengaksesnya melalui dashboard aplikasi.</p>
            
            <center>
                <a href="{{ url('/') }}" class="btn">Login ke Aplikasi</a>
            </center>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Yayasan Bersih. Sistem Arsip Digital.</p>
        </div>
    </div>
</body>
</html>

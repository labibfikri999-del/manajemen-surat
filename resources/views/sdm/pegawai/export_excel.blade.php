<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pegawai</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4f46e5; /* Indigo-600 */
            color: #ffffff;
            font-weight: bold;
            font-size: 12pt;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th style="width: 200px; background-color: #4f46e5; color: #ffffff;">Nama</th>
                <th style="width: 150px; background-color: #4f46e5; color: #ffffff;">NIP</th>
                <th style="width: 150px; background-color: #4f46e5; color: #ffffff;">NIDN</th>
                <th style="width: 100px; background-color: #4f46e5; color: #ffffff;">Role</th>
                <th style="width: 150px; background-color: #4f46e5; color: #ffffff;">Status Kepegawaian</th>
                <th style="width: 100px; background-color: #4f46e5; color: #ffffff;">Status</th>
                <th style="width: 120px; background-color: #4f46e5; color: #ffffff;">Tanggal Masuk</th>
                <th style="width: 100px; background-color: #4f46e5; color: #ffffff;">Jenis Kelamin</th>
                <th style="width: 200px; background-color: #4f46e5; color: #ffffff;">Email</th>
                <th style="width: 150px; background-color: #4f46e5; color: #ffffff;">Telepon</th>
                <th style="width: 300px; background-color: #4f46e5; color: #ffffff;">Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawais as $pegawai)
            <tr>
                <td>{{ $pegawai->name }}</td>
                <td style="mso-number-format:'\@';">{{ $pegawai->nip }}</td> <!-- Force Text format for NIP -->
                <td style="mso-number-format:'\@';">{{ $pegawai->nidn }}</td>
                <td>{{ $pegawai->role }}</td>
                <td>{{ $pegawai->status_kepegawaian }}</td>
                <td>{{ $pegawai->status }}</td>
                <td>{{ $pegawai->join_date }}</td>
                <td>{{ $pegawai->jenis_kelamin }}</td>
                <td>{{ $pegawai->email }}</td>
                <td style="mso-number-format:'\@';">{{ $pegawai->phone }}</td>
                <td>{{ $pegawai->alamat_lengkap }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['perihal'] }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.2; margin: 0; padding: 0; }
        
        /** HEADER LAYOUT **/
        .header-table { width: 100%; border-bottom: 3px double #006400; margin-bottom: 20px; padding-bottom: 10px; }
        .logo-cell { width: 100px; text-align: center; vertical-align: middle; }
        .logo-img { width: 90px; height: auto; }
        .text-cell { text-align: center; vertical-align: middle; color: #006400; }
        
        .kop-y { font-size: 16pt; font-weight: bold; letter-spacing: 3px; margin: 0; }
        .kop-org { font-size: 18pt; font-weight: bold; text-transform: uppercase; margin: 5px 0; }
        .kop-sec { font-size: 10pt; color: #000; margin: 0; }
        .kop-add { font-size: 10pt; color: #000; margin: 0; }

        /** TITLE SECTION **/
        .title-section { text-align: center; margin-bottom: 25px; }
        .surat-title { font-size: 13pt; font-weight: bold; text-decoration: underline; text-transform: uppercase; margin-bottom: 2px; }
        .surat-nomor { font-size: 11pt; font-weight: bold; margin-top: 0; }
        .label-tentang { font-size: 11pt; margin-top: 10px; margin-bottom: 2px; }
        .surat-perihal { font-size: 11pt; font-weight: bold; text-transform: uppercase; margin: 0; }

        /** CONTENT **/
        .content { margin: 0 35px; text-align: justify; }

        /** FOOTER / TTD **/
        .footer-table { width: 100%; margin-top: 40px; }
        .ttd-col { width: 40%; text-align: center; float: right; margin-right: 35px; }
        .ttd-date { margin-bottom: 5px; }
        .ttd-role { font-weight: bold; margin-bottom: 70px; } /* Space for signature */
        .ttd-name { font-weight: bold; text-decoration: underline; text-align: center; }

        /* Helper for breaks */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <!-- HEADER TABLE -->
    <table class="header-table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('images/logo_rsi_ntb_new.png') }}" class="logo-img" alt="Logo">
            </td>
            <td class="text-cell">
                <div class="kop-y">Y A Y A S A N</div>
                <div class="kop-org">RUMAH SAKIT ISLAM NUSA TENGGARA BARAT</div>
                <div class="kop-sec">Sekretariat : Rumah Sakit Islam Siti Hajar Mataram</div>
                <div class="kop-add">Jln. Caturwarga Telp. 0370-623498 Mataram NTB. Kode Pos : 83121</div>
            </td>
        </tr>
    </table>

    <!-- TITLE -->
    <div class="title-section">
        <div class="surat-title">SURAT KEPUTUSAN PENGURUS</div> 
        <div class="surat-nomor">NOMOR : {{ $data['nomor_surat'] }}</div>
        
        <div class="label-tentang">Tentang</div>
        <div class="surat-perihal">{{ $data['perihal'] }}</div>
    </div>

    <!-- CONTENT -->
    <div class="content">
        {!! nl2br(e($data['isi'])) !!}
    </div>

    <!-- TTD -->
    <div class="footer-table">
        <div class="ttd-col">
            <div class="ttd-date">
                Ditetapkan di : {{ $data['tempat'] }}<br>
                Pada Tanggal : {{ \Carbon\Carbon::parse($data['tanggal'])->locale('id')->isoFormat('D MMMM Y') }}
            </div>
            <div class="ttd-role">
                {{ $data['jabatan_ttd'] }}
            </div>
            <div class="ttd-name">
                {{ $data['nama_ttd'] }}
            </div>
        </div>
    </div>

</body>
</html>

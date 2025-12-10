<!DOCTYPE html>
<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head>
    <meta charset="utf-8">
    <title>{{ $data['perihal'] }}</title>
    <style>
        /* MSO Page Setup */
        @page Section1 {
            size: 21.0cm 29.7cm; /* A4 */
            margin: 2.54cm 2.54cm 2.54cm 2.54cm; /* Standard 1 inch margins */
            mso-header-margin: 35.4pt;
            mso-footer-margin: 35.4pt;
            mso-paper-source: 0;
        }
        div.Section1 {
            page: Section1;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.15;
            tab-interval: 36.0pt;
        }

        /* Tables */
        table {
            border-collapse: collapse;
            width: 100%;
            mso-yfti-tbllook: 1184;
            mso-padding-alt: 0cm 0cm 0cm 0cm;
        }
        
        /* Header specific */
        .kop-table {
            border-bottom: 3px double #006400;
            width: 100%;
            margin-bottom: 20px;
        }
        .kop-logo { width: 100px; padding: 0 10px 10px 0; vertical-align: middle; }
        .kop-text { text-align: center; vertical-align: middle; padding-bottom: 10px; }
        .kop-y { font-size: 14pt; font-weight: bold; letter-spacing: 2px; color: #006400; }
        .kop-org { font-size: 16pt; font-weight: bold; text-transform: uppercase; margin: 2pt 0; color: #006400; }
        .kop-addr { font-size: 10pt; color: #000; }

        /* Content */
        p { margin: 0 0 10pt 0; text-align: justify; }
        
        .title-block { text-align: center; margin: 20pt 0; }
        .surat-title { font-weight: bold; text-decoration: underline; text-transform: uppercase; font-size: 12pt; margin-bottom: 2pt; }
        .surat-nomor { font-weight: bold; margin-bottom: 10pt; }
        
        /* Footer/Signatures */
        .ttd-table { margin-top: 30pt; width: 100%; page-break-inside: avoid; }
        .ttd-col-spacer { width: 50%; }
        .ttd-col-sign { width: 50%; text-align: center; }
        .ttd-space { height: 70pt; }
    </style>
</head>
<body>
    <div class="Section1">
        <!-- HEADER -->
        <table class="kop-table" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="kop-logo" valign="middle">
                    @if(isset($data['logo_base64']))
                        <img src="{{ $data['logo_base64'] }}" width="90" height="90" alt="Logo">
                    @endif
                </td>
                <td class="kop-text" valign="middle">
                    <p class="kop-y" style="margin:0; text-align:center;">Y A Y A S A N</p>
                    <p class="kop-org" style="margin:0; text-align:center;">RUMAH SAKIT ISLAM NUSA TENGGARA BARAT</p>
                    <p class="kop-addr" style="margin:0; text-align:center;">Sekretariat : Rumah Sakit Islam Siti Hajar Mataram</p>
                    <p class="kop-addr" style="margin:0; text-align:center;">Jln. Caturwarga Telp. 0370-623498 Mataram NTB. Kode Pos : 83121</p>
                </td>
            </tr>
        </table>

        <!-- TITLE -->
        <div class="title-block">
            <p class="surat-title" style="text-align:center;">SURAT KEPUTUSAN PENGURUS</p>
            <p class="surat-nomor" style="text-align:center;">NOMOR : {{ $data['nomor_surat'] }}</p>
            <p style="text-align:center; margin-bottom:0;">Tentang</p>
            <p style="text-align:center; font-weight:bold; text-transform:uppercase;">{{ $data['perihal'] }}</p>
        </div>

        <!-- CONTENT -->
        <div style="text-align: justify;">
            {!! nl2br(e($data['isi'])) !!}
        </div>

        <!-- FOOTER / TTD -->
        <table class="ttd-table" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="ttd-col-spacer">&nbsp;</td>
                <td class="ttd-col-sign">
                    <p style="margin:0; text-align:center;">Ditetapkan di : {{ $data['tempat'] }}</p>
                    <p style="margin:0; text-align:center;">Pada Tanggal : {{ \Carbon\Carbon::parse($data['tanggal'])->locale('id')->isoFormat('D MMMM Y') }}</p>
                    <br>
                    <p style="margin:0; text-align:center; font-weight:bold;">{{ $data['jabatan_ttd'] }}</p>
                    <div class="ttd-space"></div>
                    <p style="margin:0; text-align:center; font-weight:bold; text-decoration:underline;">{{ $data['nama_ttd'] }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

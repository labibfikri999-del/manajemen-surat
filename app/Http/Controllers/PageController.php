<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function suratMasuk()
    {
        return view('surat-masuk');
    }

    public function suratKeluar()
    {
        return view('surat-keluar');
    }

    public function arsipDigital()
    {
        return view('arsip-digital');
    }

    public function laporan()
    {
        return view('laporan');
    }

    public function dataMaster()
    {
        return view('data-master');
    }
}

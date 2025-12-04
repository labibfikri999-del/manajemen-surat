<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\Klasifikasi;

class SinglePageController extends Controller {
    public function index() {
        return redirect()->route('dashboard');
    }
}

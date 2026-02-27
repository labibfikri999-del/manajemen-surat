<?php

namespace App\Http\Controllers\Aset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function unit() { return view('aset.unit.index'); }
    public function category() { return view('aset.category.index'); }
    public function loan() { return view('aset.loan.index'); }
    public function damage() { return view('aset.damage.index'); }
    public function usage() { return view('aset.usage.index'); }
    public function audit() { return view('aset.audit.index'); }
    public function scanQr() { return view('aset.scan_qr'); }
    public function settings() { return view('aset.settings'); }
}

<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\Klasifikasi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SuratApiController extends Controller {
    public function index(Request $req) {
        $q = SuratMasuk::with('klasifikasi')->latest();
        if ($req->filled('klasifikasi_id')) $q->where('klasifikasi_id', $req->klasifikasi_id);
        if ($req->filled('tanggal_diterima')) $q->where('tanggal_diterima', $req->tanggal_diterima);
        if ($req->filled('q')) {
            $qq = $req->q;
            $q->where(function($w) use ($qq){
                $w->where('nomor_surat','like',"%$qq%")
                  ->orWhere('pengirim','like',"%$qq%")
                  ->orWhere('perihal','like',"%$qq%");
            });
        }

        // server-side pagination: per_page param (fallback 10)
        $perPage = (int) ($req->per_page ?? 10);
        $data = $q->paginate($perPage)->withQueryString();
        return response()->json($data);
    }

    public function store(Request $req) {
        $v = Validator::make($req->all(), [
            'nomor_surat'=>'required|string|max:100|unique:surat_masuk',
            'tanggal_diterima'=>'required|date',
            'pengirim'=>'required|string|max:255',
            'perihal'=>'required|string|max:255',
            'klasifikasi_id'=>'nullable|exists:klasifikasi,id',
            'file'=>'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        if ($v->fails()) return response()->json(['errors'=>$v->errors()], 422);

        $data = $req->only(['nomor_surat','tanggal_diterima','pengirim','perihal','klasifikasi_id']);
        if ($req->hasFile('file')) {
            $path = $req->file('file')->store('surat','public');
            $data['file'] = $path;
        }
        $s = SuratMasuk::create($data);
        return response()->json($s->load('klasifikasi'));
    }

    public function show($id) {
        $s = SuratMasuk::with('klasifikasi')->findOrFail($id);
        return response()->json($s);
    }

    public function update(Request $req, $id) {
        $s = SuratMasuk::findOrFail($id);
        $v = Validator::make($req->all(), [
            'nomor_surat'=>'required|string|max:100|unique:surat_masuk,nomor_surat,'.$id,
            'tanggal_diterima'=>'required|date',
            'pengirim'=>'required|string|max:255',
            'perihal'=>'required|string|max:255',
            'klasifikasi_id'=>'nullable|exists:klasifikasi,id',
            'file'=>'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        if ($v->fails()) return response()->json(['errors'=>$v->errors()], 422);

        $data = $req->only(['nomor_surat','tanggal_diterima','pengirim','perihal','klasifikasi_id']);
        if ($req->hasFile('file')) {
            if ($s->file && Storage::disk('public')->exists($s->file)) Storage::disk('public')->delete($s->file);
            $path = $req->file('file')->store('surat','public');
            $data['file'] = $path;
        }
        $s->update($data);
        return response()->json($s->fresh()->load('klasifikasi'));
    }

    public function destroy($id) {
        $s = SuratMasuk::findOrFail($id);
        if ($s->file && Storage::disk('public')->exists($s->file)) Storage::disk('public')->delete($s->file);
        $s->delete();
        return response()->json(['ok'=>true]);
    }

    public function klasifikasiList() {
        return response()->json(Klasifikasi::all());
    }
}

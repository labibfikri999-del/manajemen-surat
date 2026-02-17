<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmRiwayatPangkat;
use App\Models\SDM\SdmPegawai;
use Carbon\Carbon;

class RiwayatPangkatController extends Controller
{
    public function index(Request $request)
    {
        $query = SdmRiwayatPangkat::with('pegawai');

        // Filter: Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('pegawai', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('golongan', 'like', "%{$search}%");
        }

        // Filter: Status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter: Golongan
        if ($request->has('golongan') && $request->golongan != '') {
            $query->where('golongan', $request->golongan);
        }

        $riwayats = $query->orderBy('tmt', 'desc')->paginate(10);
        $total = SdmRiwayatPangkat::count();

        return view('sdm.riwayat-pangkat.index', compact('riwayats', 'total'));
    }

    public function create()
    {
        $pegawais = SdmPegawai::orderBy('name')->get();
        return view('sdm.riwayat-pangkat.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sdm_pegawai_id' => 'required|exists:sdm_pegawais,id',
            'golongan' => 'required',
            'ruang' => 'required',
            'tmt' => 'required|date',
            'is_active' => 'boolean',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('dokumen')) {
            $path = $request->file('dokumen')->store('public/dokumen_pangkat');
            $data['dokumen_path'] = str_replace('public/', '', $path);
        }

        SdmRiwayatPangkat::create($data);

        return redirect()->route('sdm.riwayat-pangkat.index')->with('success', 'Riwayat pangkat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $riwayat = SdmRiwayatPangkat::findOrFail($id);
        $pegawais = SdmPegawai::orderBy('name')->get();
        return view('sdm.riwayat-pangkat.edit', compact('riwayat', 'pegawais'));
    }

    public function update(Request $request, $id)
    {
        $riwayat = SdmRiwayatPangkat::findOrFail($id);

        $request->validate([
            'sdm_pegawai_id' => 'required|exists:sdm_pegawais,id',
            'golongan' => 'required',
            'ruang' => 'required',
            'tmt' => 'required|date',
            'is_active' => 'boolean',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('dokumen')) {
            $path = $request->file('dokumen')->store('public/dokumen_pangkat');
            $data['dokumen_path'] = str_replace('public/', '', $path);
        }

        $riwayat->update($data);

        return redirect()->route('sdm.riwayat-pangkat.index')->with('success', 'Riwayat pangkat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $riwayat = SdmRiwayatPangkat::findOrFail($id);
        $riwayat->delete();

        return redirect()->route('sdm.riwayat-pangkat.index')->with('success', 'Riwayat pangkat berhasil dihapus.');
    }

    // Monitoring Kenaikan Pangkat (Needs separate logic or view)
    public function monitoring(Request $request)
    {
        // Logic: Calculate next promotion (e.g., 4 years from last TMT)
        // Get active ranks only
        $query = SdmRiwayatPangkat::with('pegawai')->where('is_active', true);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('pegawai', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        // Filter Year target
        $targetYear = $request->tahun ?? date('Y');

        // We need to fetch and filter in PHP because of TMT calculation complexity mostly
        // Or we can do it in SQL if DB supports date addition easily.
        // Let's do a simple implementation: fetch all active, then filter by next promotion date.
        // Next promotion = TMT + 4 years.
        
        $pangkats = $query->get()->map(function($pangkat) {
            $tmt = Carbon::parse($pangkat->tmt);
            $nextPromotion = $tmt->copy()->addYears(4);
            $pangkat->next_promotion_date = $nextPromotion;
            $pangkat->days_remaining = Carbon::now()->diffInDays($nextPromotion, false);
            $pangkat->is_due = $pangkat->days_remaining <= 90; // Due within 3 months
            return $pangkat;
        });

        if ($request->has('tahun')) {
             $pangkats = $pangkats->filter(function($p) use ($targetYear) {
                 return $p->next_promotion_date->year == $targetYear;
             });
        }

        // Pagination manually if needed, or just display all for monitoring
        $totalPegawai = SdmPegawai::where('status', 'active')->count();
        $siapNaik = $pangkats->where('days_remaining', '<=', 0)->count();
        $segeraNaik = $pangkats->whereBetween('days_remaining', [1, 90])->count();
        $dalamEnamBulan = $pangkats->whereBetween('days_remaining', [1, 180])->count();

        return view('sdm.monitoring-pangkat.index', compact('pangkats', 'siapNaik', 'segeraNaik', 'dalamEnamBulan', 'totalPegawai', 'targetYear'));
    }
}

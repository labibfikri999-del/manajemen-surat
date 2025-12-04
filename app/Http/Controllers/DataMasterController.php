<?php

namespace App\Http\Controllers;

use App\Models\Klasifikasi;
use Illuminate\Http\Request;

class DataMasterController extends Controller
{
    // Get all klasifikasi
    public function indexKlasifikasi()
    {
        return response()->json(Klasifikasi::latest()->get());
    }

    // Store klasifikasi
    public function storeKlasifikasi(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|unique:klasifikasi',
        ]);

        $klasifikasi = Klasifikasi::create($validated);
        return response()->json($klasifikasi, 201);
    }

    // Update klasifikasi
    public function updateKlasifikasi(Request $request, $id)
    {
        $klasifikasi = Klasifikasi::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|unique:klasifikasi,nama,' . $id,
        ]);

        $klasifikasi->update($validated);
        return response()->json($klasifikasi);
    }

    // Delete klasifikasi
    public function destroyKlasifikasi($id)
    {
        Klasifikasi::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}

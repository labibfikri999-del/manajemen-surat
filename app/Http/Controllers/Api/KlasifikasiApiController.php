<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Klasifikasi;
use Illuminate\Http\Request;

class KlasifikasiApiController extends Controller
{
    public function index()
    {
        $klas = Klasifikasi::orderBy('nama')->get();

        return response()->json($klas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:klasifikasis,nama',
        ]);

        $klas = Klasifikasi::create($validated);

        return response()->json($klas, 201);
    }

    public function show($id)
    {
        $klas = Klasifikasi::findOrFail($id);

        return response()->json($klas);
    }

    public function update(Request $request, $id)
    {
        $klas = Klasifikasi::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:klasifikasis,nama,'.$id,
        ]);

        $klas->update($validated);

        return response()->json($klas);
    }

    public function destroy($id)
    {
        $klas = Klasifikasi::findOrFail($id);
        $klas->delete();

        return response()->json(['message' => 'Klasifikasi deleted']);
    }
}

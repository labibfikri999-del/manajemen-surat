<?php

namespace App\Http\Controllers\Aset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aset\Aset;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Filter by Category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filter by Condition
        if ($request->has('condition') && $request->condition != '') {
            $query->where('condition', $request->condition);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Allow sorting by valid columns only
        if (in_array($sortField, ['name', 'code', 'price', 'purchase_date', 'created_at'])) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $asets = $query->paginate(10)->withQueryString();

        // Stats for the view
        $stats = [
            'total' => Aset::count(),
            'baik' => Aset::where('condition', 'Baik')->count(),
            'rusak' => Aset::whereIn('condition', ['Rusak Ringan', 'Rusak Berat'])->count(),
            'value' => Aset::sum('price'),
        ];
        
        // Get unique categories for filter dropdown
        $categories = Aset::select('category')->distinct()->pluck('category');

        return view('aset.inventory.index', compact('asets', 'stats', 'categories'));
    }

    public function create()
    {
        $newCode = 'AST-' . date('Y') . '-' . str_pad(Aset::count() + 1, 3, '0', STR_PAD_LEFT);
        return view('aset.inventory.create', compact('newCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required',
            'location' => 'required',
            'purchase_date' => 'nullable|date',
            'price' => 'nullable|numeric',
            'photo' => 'nullable|image|max:2048',
        ]);

        $code = 'AST-' . date('Y') . '-' . strtoupper(Str::random(4)); // Fallback or strict generation logic
        
        // Manual override or auto-gen check
        if($request->code) {
             $code = $request->code;
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('aset-photos', 'public');
        }

        Aset::create([
            'code' => $code,
            'name' => $request->name,
            'category' => $request->category,
            'brand' => $request->brand,
            'model' => $request->model,
            'location' => $request->location,
            'condition' => $request->condition ?? 'Baik',
            'purchase_date' => $request->purchase_date,
            'price' => $request->price ?? 0,
            'photo' => $photoPath,
            'notes' => $request->notes,
        ]);

        return redirect()->route('aset.inventory.index')->with('success', 'Aset baru berhasil ditambahkan.');
    }

    public function show($id)
    {
        $aset = Aset::with(['mutations', 'maintenances'])->findOrFail($id);
        return view('aset.inventory.show', compact('aset'));
    }

    public function edit($id)
    {
        $aset = Aset::findOrFail($id);
        return view('aset.inventory.edit', compact('aset'));
    }

    public function update(Request $request, $id)
    {
        $aset = Aset::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required',
            'location' => 'required',
            'price' => 'nullable|numeric',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($aset->photo) {
                Storage::disk('public')->delete($aset->photo);
            }
            $aset->photo = $request->file('photo')->store('aset-photos', 'public');
        }

        $aset->update([
            'name' => $request->name,
            'category' => $request->category,
            'brand' => $request->brand,
            'model' => $request->model,
            'location' => $request->location,
            'condition' => $request->condition,
            'purchase_date' => $request->purchase_date,
            'price' => $request->price,
            'notes' => $request->notes,
        ]);

        return redirect()->route('aset.inventory.show', $aset->id)->with('success', 'Data aset diperbarui.');
    }

    public function destroy($id)
    {
        $aset = Aset::findOrFail($id);
        if ($aset->photo) {
            Storage::disk('public')->delete($aset->photo);
        }
        $aset->delete();

        return redirect()->route('aset.inventory.index')->with('success', 'Aset dihapus.');
    }
}

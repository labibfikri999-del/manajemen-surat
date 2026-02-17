<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmSetting;
use App\Models\User;
use App\Models\Instansi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SdmSetting::all()->mapWithKeys(function ($item) {
            return [$item->key => $item->value];
        });

        $users = User::with('instansi')->latest()->get();
        $instansis = Instansi::all();

        return view('sdm.settings.index', compact('settings', 'users', 'instansis'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token', '_method');

        foreach ($data as $key => $value) {
            SdmSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('sdm.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:direktur,staff,instansi',
            'instansi_id' => 'required_if:role,instansi|nullable|exists:instansis,id',
        ]);

        $validated['plain_password'] = $validated['password'];
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()->route('sdm.settings.index', ['tab' => 'users'])
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string',
            'username' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:direktur,staff,instansi',
            'instansi_id' => 'required_if:role,instansi|nullable|exists:instansis,id',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'min:6';
        }

        $validated = $request->validate($rules);

        if ($request->filled('password')) {
            $validated['plain_password'] = $validated['password'];
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('sdm.settings.index', ['tab' => 'users'])
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroyUser($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('sdm.settings.index', ['tab' => 'users'])
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ward;
use Illuminate\Http\Request;

class WardController extends Controller
{
    public function index()
    {
        $wards = Ward::orderBy('id')->get();

        return view('admin.wards.index', [
            'wards' => $wards,
            'navItems' => $this->adminNavigation(),
        ]);
    }

    public function create()
    {
        return view('admin.wards.create', [
            'navItems' => $this->adminNavigation(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => 'required|max:255',

            'capacity' => 'required|integer|min:1',

            'description' => 'nullable',

        ]);

        Ward::create($validated);

        return redirect()
            ->route('admin.wards.index')
            ->with('success', 'Bangsal berhasil ditambahkan.');
    }

    public function show(Ward $ward)
    {
        //
    }

    public function edit(Ward $ward)
    {
        return view('admin.wards.edit', [
            'ward' => $ward,
            'navItems' => $this->adminNavigation(),
        ]);
    }

    public function update(Request $request, Ward $ward)
    {
        $validated = $request->validate([

            'name' => 'required|max:255',

            'capacity' => 'required|integer|min:1',

            'description' => 'nullable',

        ]);

        $ward->update($validated);

        return redirect()
            ->route('admin.wards.index')
            ->with('success', 'Bangsal berhasil diupdate.');
    }

    public function destroy(Ward $ward)
    {
        if ($ward->rooms()->exists()) {

            return redirect()
                ->route('admin.wards.index')
                ->with(
                    'error',
                    'Bangsal tidak dapat dihapus karena masih memiliki ruangan. Hapus atau pindahkan semua ruangan terlebih dahulu.'
                );
        }

        $ward->delete();

        return redirect()
            ->route('admin.wards.index')
            ->with('success', 'Bangsal berhasil dihapus.');
    }

    private function adminNavigation(): array
    {
        return \App\Support\AdminMenu::items();
    }
}

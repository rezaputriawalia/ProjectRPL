<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Ward;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('ward')
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->orderBy('wards.name')
            ->orderBy('rooms.name')
            ->select('rooms.*')
            ->get();

        return view('admin.rooms.index', [
            'rooms' => $rooms,
            'navItems' => $this->adminNavigation(),
        ]);
    }

    public function create()
    {
        return view('admin.rooms.create', [
            'wards' => Ward::orderBy('name')->get(),
            'navItems' => $this->adminNavigation(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'ward_id' => 'required|exists:wards,id',

            'name' => 'required|max:255',

            'capacity' => 'required|integer|min:1',

            'status' => 'required|in:available,full,maintenance',

        ]);

        $ward = Ward::findOrFail($validated['ward_id']);

        $totalCapacity = Room::where('ward_id', $validated['ward_id'])
            ->sum('capacity');

        if (($totalCapacity + $validated['capacity']) > $ward->capacity) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Total kapasitas seluruh ruangan melebihi kapasitas bangsal (' . $ward->capacity . ' tempat tidur).'
                );
        }

        Room::create($validated);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show(Room $room) {}

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', [
            'room' => $room,
            'wards' => Ward::orderBy('name')->get(),
            'navItems' => $this->adminNavigation(),
        ]);
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([

            'ward_id' => 'required|exists:wards,id',

            'name' => 'required|max:255',

            'capacity' => 'required|integer|min:1',

            'status' => 'required|in:available,full,maintenance',

        ]);

        $ward = Ward::findOrFail($validated['ward_id']);

        $totalCapacity = Room::where('ward_id', $validated['ward_id'])
            ->where('id', '!=', $room->id)
            ->sum('capacity');

        if (($totalCapacity + $validated['capacity']) > $ward->capacity) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Total kapasitas seluruh ruangan melebihi kapasitas bangsal (' . $ward->capacity . ' tempat tidur).'
                );
        }

        $room->update($validated);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil diupdate.');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }

    private function adminNavigation(): array
    {
        return \App\Support\AdminMenu::items();
    }
}

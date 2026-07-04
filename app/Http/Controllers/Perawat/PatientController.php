<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Registration;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        abort_if(!$user, 403);

        $registrations = Registration::with([
            'patient',
            'doctor',
            'room.ward'
        ])
            ->where('status', 'active')
            ->whereHas('room', function ($query) use ($user) {
                $query->where('ward_id', $user->ward_id);
            })
            ->orderBy('patient_id')
            ->get();

        return view('perawat.patients.index', [

            'registrations' => $registrations,

            'navItems' => \App\Support\NurseMenu::items(),

        ]);
    }

    public function create()
    {
        $user = Auth::user();

        abort_if(!$user, 403);

        $rooms = Room::where('ward_id', $user->ward_id)
            ->where('status', 'available')
            ->orderBy('name')
            ->get();

        $doctors = User::whereHas(
            'role',
            fn($q) => $q->where('name', 'doctor')
        )
            ->orderBy('name')
            ->get();

        return view('perawat.patients.create', [

            'rooms' => $rooms,

            'doctors' => $doctors,

            'navItems' => \App\Support\NurseMenu::items(),

        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'doctor_id' => 'required|exists:users,id',

            'room_id' => 'required|exists:rooms,id',

            'name' => 'required|max:255',

            'nik' => 'required|unique:patients,nik',

            'gender' => 'required|in:L,P',

            'birth_date' => 'required|date',

            'address' => 'required',

            'phone' => 'nullable|max:20',

        ]);

        $room = Room::findOrFail($validated['room_id']);

        $jumlahPasien = Registration::where('room_id', $room->id)
            ->where('status', 'active')
            ->count();

        if ($jumlahPasien >= $room->capacity) {

            return back()
                ->withErrors([
                    'room_id' => 'Ruangan sudah penuh.'
                ])
                ->withInput();
        }

        $next = Patient::max('id') + 1;

        $rm = 'RM-' . str_pad($next, 6, '0', STR_PAD_LEFT);

        $patient = Patient::create([

            'medical_record_number' => $rm,

            'name' => $validated['name'],

            'nik' => $validated['nik'],

            'gender' => $validated['gender'],

            'birth_date' => $validated['birth_date'],

            'address' => $validated['address'],

            'phone' => $validated['phone'],

        ]);

        Registration::create([

            'patient_id' => $patient->id,

            'doctor_id' => $validated['doctor_id'],

            'nurse_id' => Auth::id(),

            'room_id' => $validated['room_id'],

            'admission_date' => today(),

            'status' => 'active',

        ]);

        $this->updateRoomStatus($room->id);

        return redirect()
            ->route('perawat.patients.index')
            ->with('success', 'Pasien berhasil diregistrasi.');
    }

    public function show(Patient $patient)
    {
        return redirect()->route('perawat.patients.index');
    }

    public function edit(Patient $patient)
    {
        $user = Auth::user();

        abort_if(!$user, 403);

        $registration = $patient->registrations()
            ->where('status', 'active')
            ->firstOrFail();

        abort_if(
            $registration->room->ward_id != $user->ward_id,
            403
        );

        $rooms = Room::where('ward_id', $user->ward_id)
            ->orderBy('name')
            ->get();

        $doctors = User::whereHas(
            'role',
            fn($q) => $q->where('name', 'doctor')
        )
            ->orderBy('name')
            ->get();

        return view('perawat.patients.edit', [

            'patient' => $patient,

            'registration' => $registration,

            'rooms' => $rooms,

            'doctors' => $doctors,

            'navItems' => \App\Support\NurseMenu::items(),

        ]);
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([

            'doctor_id' => 'required|exists:users,id',

            'room_id' => 'required|exists:rooms,id',

            'name' => 'required|max:255',

            'nik' => 'required|unique:patients,nik,' . $patient->id,

            'gender' => 'required|in:L,P',

            'birth_date' => 'required|date',

            'address' => 'required',

            'phone' => 'nullable|max:20',

        ]);

        $registration = $patient->registrations()
            ->where('status', 'active')
            ->firstOrFail();

        $oldRoomId = $registration->room_id;

        $newRoom = Room::findOrFail($validated['room_id']);

        $jumlahPasien = Registration::where('room_id', $newRoom->id)
            ->where('status', 'active')
            ->where('patient_id', '!=', $patient->id)
            ->count();

        if ($jumlahPasien >= $newRoom->capacity) {

            return back()
                ->withErrors([
                    'room_id' => 'Ruangan sudah penuh.'
                ])
                ->withInput();
        }

        $patient->update([

            'name' => $validated['name'],

            'nik' => $validated['nik'],

            'gender' => $validated['gender'],

            'birth_date' => $validated['birth_date'],

            'address' => $validated['address'],

            'phone' => $validated['phone'],

        ]);

        $registration->update([

            'doctor_id' => $validated['doctor_id'],

            'room_id' => $validated['room_id'],

        ]);

        $this->updateRoomStatus($oldRoomId);

        $this->updateRoomStatus($validated['room_id']);

        return redirect()
            ->route('perawat.patients.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Patient $patient)
    {
        $registration = $patient->registrations()
            ->where('status', 'active')
            ->first();

        $roomId = $registration?->room_id;

        $patient->delete();

        $this->updateRoomStatus($roomId);

        return redirect()
            ->route('perawat.patients.index')
            ->with('success', 'Pasien berhasil dihapus.');
    }

    private function updateRoomStatus(?int $roomId): void
    {
        if (!$roomId) {
            return;
        }

        $room = Room::find($roomId);

        if (!$room) {
            return;
        }

        $jumlahPasien = Registration::where('room_id', $room->id)
            ->where('status', 'active')
            ->count();

        $room->update([

            'status' => $jumlahPasien >= $room->capacity
                ? 'full'
                : 'available',

        ]);
    }
}
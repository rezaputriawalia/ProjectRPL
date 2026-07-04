<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Registration;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $registrations = Registration::with([
            'patient',
            'doctor',
            'room.ward',
            'nurse',
        ])
        ->where('status', 'active')
        ->orderBy('patient_id')
        ->get();

        return view('admin.patients.index', [
            'registrations' => $registrations,
            'navItems' => $this->adminNavigation(),
        ]);
    }

    public function create()
    {
        return view('admin.patients.create', [

            'doctors' => User::whereHas(
                'role',
                fn($q) => $q->where('name', 'doctor')
            )->orderBy('name')->get(),

            'nurses' => User::whereHas(
                'role',
                fn($q) => $q->where('name', 'nurse')
            )->orderBy('name')->get(),

            'rooms' => Room::with('ward')
                ->orderBy('name')
                ->get(),

            'navItems' => $this->adminNavigation(),

        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'doctor_id' => 'required|exists:users,id',

            'nurse_id' => 'required|exists:users,id',

            'room_id' => 'required|exists:rooms,id',

            'name' => 'required|max:255',

            'nik' => 'required|unique:patients,nik',

            'gender' => 'required|in:L,P',

            'birth_date' => 'required|date',

            'address' => 'required',

            'phone' => 'nullable|max:20',

        ]);

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

            'nurse_id' => $validated['nurse_id'],

            'room_id' => $validated['room_id'],

            'admission_date' => today(),

            'status' => 'active',

        ]);

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Pasien berhasil ditambahkan.');
    }

    public function edit(Patient $patient)
    {
        $registration = $patient->registrations()
            ->where('status', 'active')
            ->firstOrFail();

        return view('admin.patients.edit', [

            'patient' => $patient,

            'registration' => $registration,

            'doctors' => User::whereHas(
                'role',
                fn($q) => $q->where('name', 'doctor')
            )->orderBy('name')->get(),

            'nurses' => User::whereHas(
                'role',
                fn($q) => $q->where('name', 'nurse')
            )->orderBy('name')->get(),

            'rooms' => Room::with('ward')
                ->orderBy('name')
                ->get(),

            'navItems' => $this->adminNavigation(),

        ]);
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([

            'doctor_id' => 'required|exists:users,id',

            'nurse_id' => 'required|exists:users,id',

            'room_id' => 'required|exists:rooms,id',

            'name' => 'required|max:255',

            'nik' => 'required|unique:patients,nik,' . $patient->id,

            'gender' => 'required|in:L,P',

            'birth_date' => 'required|date',

            'address' => 'required',

            'phone' => 'nullable|max:20',

        ]);

        $patient->update([

            'name' => $validated['name'],

            'nik' => $validated['nik'],

            'gender' => $validated['gender'],

            'birth_date' => $validated['birth_date'],

            'address' => $validated['address'],

            'phone' => $validated['phone'],

        ]);

        Registration::where('patient_id', $patient->id)
            ->where('status', 'active')
            ->update([

                'doctor_id' => $validated['doctor_id'],

                'nurse_id' => $validated['nurse_id'],

                'room_id' => $validated['room_id'],

            ]);

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Patient $patient)
    {
        Registration::where('patient_id', $patient->id)->delete();

        $patient->delete();

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Pasien berhasil dihapus.');
    }

    private function adminNavigation(): array
    {
        return \App\Support\AdminMenu::items();
    }
}
<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\Cppt;
use App\Models\Monitoring;
use App\Models\Patient;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MonitoringItem;
use App\Models\CpptActionPhoto;

class CpptController extends Controller
{
    public function index(Patient $patient)
    {
        $registration = $patient->registrations()
            ->with(['doctor', 'room'])
            ->where('status', 'active')
            ->firstOrFail();

        $cppts = Cppt::with([
            'doctor',
            'nurse',
            'verifier',
            'registration'
        ])
            ->where('registration_id', $registration->id)
            ->latest()
            ->get();

        return view('perawat.cppts.index', [
            'patient' => $patient,
            'registration' => $registration,
            'cppts' => $cppts,
            'navItems' => \App\Support\NurseMenu::items(),
        ]);
    }

    public function create(Patient $patient)
    {
        $registration = $patient->registrations()
            ->where('status', 'active')
            ->firstOrFail();

        $monitoring = Monitoring::with('items')
            ->where('registration_id', $registration->id)
            ->whereDate('monitoring_date', today())
            ->first();

        if (!$monitoring) {
            return redirect()
                ->route('perawat.patients.monitorings.create', $patient)
                ->with('error', 'Silakan isi Monitoring Tindakan terlebih dahulu.');
        }

        return view('perawat.cppts.create', [
            'patient' => $patient,
            'registration' => $registration,
            'monitoring' => $monitoring,
            'navItems' => \App\Support\NurseMenu::items(),
        ]);
    }

    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'selected_actions' => 'nullable|array',

            'monitoring_note' => 'nullable|string',

            'subjective' => 'required|string',

            'objective' => 'required|string',

            'assessment' => 'required|string',

            'plan' => 'required|string',

            'photos' => 'nullable|array',

            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $registration = $patient->registrations()
            ->where('status', 'active')
            ->firstOrFail();

        $todayCppt = Cppt::where('registration_id', $registration->id)
            ->whereDate('created_at', today())
            ->exists();

        if ($todayCppt) {
            return redirect()
                ->route('perawat.patients.index')
                ->with('error', 'CPPT pasien hari ini sudah dibuat.');
        }

        // $photo = null;

        // if ($request->hasFile('photo')) {
        //     $photo = $request->file('photo')->store('cppts', 'public');
        // }

        $cppt = Cppt::create([
            'registration_id' => $registration->id,
            'doctor_id' => $registration->doctor_id,
            'nurse_id' => Auth::id(),
            'subjective' => $validated['subjective'],
            'objective' => $validated['objective'],
            'assessment' => $validated['assessment'],
            'plan' => $validated['plan'],
            'selected_actions' => $validated['selected_actions'] ?? [],
            'monitoring_note' => $validated['monitoring_note'] ?? null,
            // 'photo' => $photo,
            'verification_status' => 'pending',
        ]);

        $selectedActions = $validated['selected_actions'] ?? [];

        foreach ($selectedActions as $action) {

            if (!$request->hasFile("photos.$action")) {

                return back()
                    ->withErrors([
                        'photos' => "Foto untuk tindakan '$action' wajib diunggah."
                    ])
                    ->withInput();
            }

            $path = $request
                ->file("photos.$action")
                ->store('cppt-actions', 'public');

            $monitoringItem = MonitoringItem::where('action', $action)->first();

            CpptActionPhoto::create([

                'cppt_id' => $cppt->id,

                'action_name' => $action,

                'category' => $monitoringItem?->category ?? '-',

                'photo' => $path,

            ]);
        }

        return redirect()
            ->route('perawat.patients.cppts.index', $patient)
            ->with('success', 'CPPT berhasil disimpan.');
    }

    public function show(Patient $patient, Cppt $cppt)
    {
        $registration = $patient->registrations()
            ->where('status', 'active')
            ->firstOrFail();

        $cppt->load('actionPhotos');

        return view('perawat.cppts.show', [

            'patient' => $patient,

            'registration' => $registration,

            'cppt' => $cppt,

            'navItems' => \App\Support\NurseMenu::items(),

        ]);
    }
}

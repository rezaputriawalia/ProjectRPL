<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\Monitoring;
use App\Models\MonitoringItem;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Patient $patient)
    {
        $registration = Registration::where('patient_id', $patient->id)
            ->where('status', 'active')
            ->firstOrFail();

        $monitoring = Monitoring::with('items')

            ->where('registration_id', $registration->id)

            ->whereDate('monitoring_date', today())

            ->first();

        return view('perawat.monitorings.create', [

            'patient' => $patient,

            'registration' => $registration,

            'monitoring' => $monitoring,

            'navItems' => \App\Support\NurseMenu::items(),

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Patient $patient)
    {
        $request->validate([

            'tak' => 'nullable|string',

            'adl' => 'nullable|string',

        ]);

        $registration = Registration::where(
            'patient_id',
            $patient->id
        )
            ->where(
                'status',
                'active'
            )
            ->firstOrFail();

        $monitoring = Monitoring::updateOrCreate(

            [

                'registration_id' => $registration->id,

                'monitoring_date' => today(),

            ],

            [

                'nurse_id' => Auth::id(),

            ]

        );

        // Hapus tindakan lama
        $monitoring->items()->delete();

        // Simpan TAK
        if ($request->filled('tak')) {

            $takItems = preg_split('/\r\n|\r|\n/', trim($request->tak));

            foreach ($takItems as $item) {

                if (trim($item) != '') {

                    MonitoringItem::create([

                        'monitoring_id' => $monitoring->id,

                        'category' => 'TAK',

                        'action' => trim($item),

                    ]);
                }
            }
        }

        // Simpan ADL
        if ($request->filled('adl')) {

            $adlItems = preg_split('/\r\n|\r|\n/', trim($request->adl));

            foreach ($adlItems as $item) {

                if (trim($item) != '') {

                    MonitoringItem::create([

                        'monitoring_id' => $monitoring->id,

                        'category' => 'ADL',

                        'action' => trim($item),

                    ]);
                }
            }
        }

        return redirect()

            ->route('perawat.patients.index')

            ->with('monitoring_success', true)

            ->with('patient_id', $patient->id)

            ->with('patient_name', $patient->name);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

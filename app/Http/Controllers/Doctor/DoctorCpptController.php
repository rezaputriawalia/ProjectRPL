<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Cppt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MonitoringItem;

class DoctorCpptController extends Controller
{
    public function index()
    {
        $cppts = Cppt::with([
            'registration.patient',
            'registration.room',
            'nurse',
            'verifier'
        ])
            ->where('doctor_id', Auth::id())
            ->latest()
            ->get();

        return view('doctor.cppts.index', [

            'cppts' => $cppts,

            'navItems' => \App\Support\DoctorMenu::items(),

        ]);
    }

    public function show(Cppt $cppt)
    {
        abort_if(
            $cppt->doctor_id != Auth::id(),
            403
        );

        $cppt->load('actionPhotos');

        $selectedActions = MonitoringItem::whereIn(
            'id',
            $cppt->selected_actions ?? []
        )->get();

        return view('doctor.cppts.show', [

            'cppt' => $cppt,
            'selectedActions' => $selectedActions,

            'navItems' => \App\Support\DoctorMenu::items(),

        ]);
    }

    public function update(Request $request, Cppt $cppt)
    {
        abort_if(
            $cppt->doctor_id != Auth::id(),
            403
        );

        $cppt->update([

            'verification_status' => 'verified',

            'verified_by' => Auth::id(),

            'verified_at' => now(),

            'doctor_note' => $request->doctor_note,

        ]);

        return redirect()

            ->route('doctor.cppts.index')

            ->with(

                'success',

                'CPPT berhasil diverifikasi.'

            );
    }
}

<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Cppt;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        $doctor = Auth::user();

        $pending = Cppt::where('doctor_id', $doctor->id)
            ->where('verification_status', 'pending')
            ->count();

        $verified = Cppt::where('doctor_id', $doctor->id)
            ->where('verification_status', 'verified')
            ->count();

        $today = Cppt::where('doctor_id', $doctor->id)
            ->whereDate('created_at', today())
            ->count();

        $patients = Registration::with([
                'patient',
                'room',
                'cppts'
            ])
            ->where('doctor_id', $doctor->id)
            ->where('status', 'active')
            ->orderBy('patient_id')
            ->get();

        return view('doctor.dashboard', [

            'pending' => $pending,

            'verified' => $verified,

            'today' => $today,

            'patients' => $patients,

            'navItems' => \App\Support\DoctorMenu::items(),

        ]);
    }
}
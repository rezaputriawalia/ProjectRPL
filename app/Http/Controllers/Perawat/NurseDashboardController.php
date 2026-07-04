<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NurseDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        abort_if(!$user, 403);

        $wardId = $user->ward_id;

        $registrations = Registration::with([
            'patient',
            'doctor',
            'room'
        ])
        ->where('status', 'active')
        ->whereHas('room', function ($query) use ($wardId) {
            $query->where('ward_id', $wardId);
        })
        ->latest()
        ->take(5)
        ->get();

        $totalPatients = Registration::where('status', 'active')
            ->whereHas('room', function ($query) use ($wardId) {
                $query->where('ward_id', $wardId);
            })
            ->count();

        $rawatInap = Registration::where('status', 'active')
            ->whereHas('room', function ($query) use ($wardId) {
                $query->where('ward_id', $wardId);
            })
            ->count();

        $rawatJalan = 0;

        $totalRooms = Room::where('ward_id', $wardId)->count();

        return view('perawat.dashboard', [

            'navItems' => \App\Support\NurseMenu::items(),

            'registrations' => $registrations,

            'totalPatients' => $totalPatients,

            'rawatInap' => $rawatInap,

            'rawatJalan' => $rawatJalan,

            'totalRooms' => $totalRooms,

        ]);
    }
}
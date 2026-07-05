<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
// use App\Support\AdminMenu;

class AdminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->string('period')->lower()->value() === 'bulanan' ? 'bulanan' : 'mingguan';
        $admin = Auth::user();

        $roleCounts = $this->roleCounts();
        $wardSeries = $this->wardPatientSeries($period);

        return view('admin.dashboard', [
            'admin' => $admin,
            'period' => $period,
            // 'navItems' => AdminMenu::items(),
            'stats' => [
                [
                    'label' => 'Total Perawat',
                    'value' => $roleCounts['nurse'],
                    'description' => 'Perawat aktif',
                    'icon' => 'fa-solid fa-user-nurse',
                    'accent' => 'green',
                ],
                [
                    'label' => 'Total Dokter',
                    'value' => $roleCounts['doctor'],
                    'description' => 'Aktif melayani',
                    'icon' => 'fa-solid fa-user-doctor',
                    'accent' => 'gold',
                ],
                [
                    'label' => 'Total Bangsal',
                    'value' => $this->totalWards(),
                    'description' => 'Bangsal aktif',
                    'icon' => 'fa-solid fa-hospital',
                    'accent' => 'brown',
                ],
                [
                    'label' => 'Pasien Aktif',
                    'value' => $this->activePatients(),
                    'description' => 'Sedang dirawat',
                    'icon' => 'fa-solid fa-bed-pulse',
                    'accent' => 'green',
                ],
            ],
            'wardChart' => [
                'labels' => $wardSeries->pluck('ward')->values(),
                'data' => $wardSeries->pluck('total')->values(),
            ],
            'wardCapacities' => $this->wardCapacities(),
            'activities' => $this->latestActivities(),
            'notifications' => $this->systemNotifications(),
            'userSummary' => [
                'labels' => ['Admin', 'Dokter', 'Perawat'],
                'data' => [$roleCounts['admin'], $roleCounts['doctor'], $roleCounts['nurse']],
            ],
        ]);
    }

    // private function adminNavigation(): array
    // {
    //     return [
    //         ['key' => 'beranda', 'label' => 'Beranda', 'href' => route('admin.dashboard'), 'icon' => 'fa-solid fa-table-cells-large'],
    //         ['key' => 'manajemen-user', 'label' => 'Manajemen User', 'href' => route('admin.users.index'), 'icon' => 'fa-solid fa-users-gear'],
    //         ['key' => 'kelola-bangsal', 'label' => 'Kelola Bangsal', 'href' => '#', 'icon' => 'fa-solid fa-hospital'],
    //         ['key' => 'kelola-dokter', 'label' => 'Kelola Dokter', 'href' => '#', 'icon' => 'fa-solid fa-user-doctor'],
    //         ['key' => 'kelola-perawat', 'label' => 'Kelola Perawat', 'href' => '#', 'icon' => 'fa-solid fa-user-nurse'],
    //         ['key' => 'laporan', 'label' => 'Laporan', 'href' => '#', 'icon' => 'fa-solid fa-file-lines'],
    //         ['key' => 'pengaturan', 'label' => 'Pengaturan', 'href' => '#', 'icon' => 'fa-solid fa-gear'],
    //         ['key' => 'logout', 'label' => 'Logout', 'href' => '#', 'icon' => 'fa-solid fa-right-from-bracket'],
    //     ];
    // }

    private function roleCounts(): array
    {
        if (! Schema::hasTable('users') || ! Schema::hasTable('roles')) {
            return ['admin' => 0, 'doctor' => 0, 'nurse' => 0];
        }

        $rows = DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->select('roles.name', DB::raw('COUNT(users.id) as total'))
            ->where('users.status', 'active')
            ->whereIn('roles.name', ['admin', 'doctor', 'nurse'])
            ->groupBy('roles.name')
            ->pluck('total', 'name');

        return [
            'admin' => (int) ($rows['admin'] ?? 0),
            'doctor' => (int) ($rows['doctor'] ?? 0),
            'nurse' => (int) ($rows['nurse'] ?? 0),
        ];
    }

    private function totalWards(): int
    {
        if (!Schema::hasTable('wards')) {
            return 0;
        }

        return (int) DB::table('wards')->count();
    }

    private function activePatients(): int
    {
        if (Schema::hasTable('registrations')) {
            return (int) DB::table('registrations')
                ->where('status', 'active')
                ->distinct()
                ->count('patient_id');
        }

        return Schema::hasTable('patients') ? (int) DB::table('patients')->count() : 0;
    }

    private function wardPatientSeries(string $period): Collection
    {
        if (!Schema::hasTable('registrations')) {
            return collect();
        }

        $from = $period === 'bulanan'
            ? Carbon::now()->startOfMonth()
            : Carbon::now()->startOfWeek();

        return DB::table('registrations')
            ->join('rooms', 'rooms.id', '=', 'registrations.room_id')
            ->join('wards', 'wards.id', '=', 'rooms.ward_id')
            ->selectRaw('wards.name as ward, COUNT(DISTINCT registrations.patient_id) as total')
            ->where('registrations.admission_date', '>=', $from)
            ->groupBy('wards.name')
            ->orderBy('wards.name')
            ->get()
            ->map(fn($row) => [
                'ward' => $row->ward,
                'total' => (int) $row->total,
            ]);
    }

    private function wardCapacities(): Collection
    {
        if (
            !Schema::hasTable('wards') ||
            !Schema::hasTable('registrations')
        ) {
            return collect();
        }

        return DB::table('wards')
            ->leftJoin('rooms', 'rooms.ward_id', '=', 'wards.id')
            ->leftJoin('registrations', function ($join) {
                $join->on('registrations.room_id', '=', 'rooms.id')
                    ->where('registrations.status', '=', 'active');
            })
            ->select(
                'wards.id',
                'wards.name',
                'wards.capacity',
                DB::raw('COUNT(DISTINCT registrations.patient_id) as occupied')
            )
            ->groupBy('wards.id', 'wards.name', 'wards.capacity')
            ->orderBy('wards.name')
            ->get()
            ->map(fn($row) => $this->formatWardCapacity(
                $row->name,
                (int) $row->occupied,
                (int) $row->capacity
            ));
    }

    private function formatWardCapacity(string $name, int $occupied, int $capacity): array
    {
        $capacity = max($capacity, 1);
        $percentage = min(100, (int) round(($occupied / $capacity) * 100));

        return [
            'name' => $name,
            'occupied' => $occupied,
            'capacity' => $capacity,
            'percentage' => $percentage,
            'tone' => $percentage > 90 ? 'danger' : ($percentage >= 75 ? 'warning' : 'success'),
        ];
    }

    private function latestActivities(): Collection
    {
        if (! Schema::hasTable('activity_logs')) {
            return collect();
        }

        return DB::table('activity_logs')
            ->leftJoin('users', 'users.id', '=', 'activity_logs.user_id')
            ->select([
                'activity_logs.activity',
                'activity_logs.description',
                'activity_logs.created_at',
                'users.name as user_name',
            ])
            ->orderByDesc('activity_logs.created_at')
            ->limit(8)
            ->get()
            ->map(fn($row) => [
                'action' => $row->activity,
                'description' => $row->description,
                'user_name' => $row->user_name,
                'time' => Carbon::parse($row->created_at)->diffForHumans(),
                'icon' => $this->activityIcon($row->activity),
            ]);
    }

    private function systemNotifications(): Collection
    {
        if (Schema::hasTable('notifications')) {
            return DB::table('notifications')
                ->select([
                    'title',
                    'message',
                    'type',
                    'created_at',
                ])
                ->orderByDesc('created_at')
                ->limit(5)
                ->get()
                ->map(fn($row) => [
                    'title' => $row->title,
                    'description' => $row->message,
                    'tone' => $row->type,
                    'icon' => 'fa-solid fa-bell',
                ]);
        }

        $notifications = collect();

        $this->wardCapacities()
            ->whereIn('tone', ['warning', 'danger'])
            ->take(2)
            ->each(function ($ward) use ($notifications) {
                $notifications->push([
                    'title' => $ward['tone'] === 'danger' ? 'Bangsal hampir penuh' : 'Kapasitas bangsal meningkat',
                    'description' => $ward['name'] . ' terisi ' . $ward['occupied'] . ' dari ' . $ward['capacity'] . ' tempat tidur.',
                    'tone' => $ward['tone'],
                    'icon' => 'fa-solid fa-bed',
                ]);
            });

        $pendingCppt = $this->pendingCpptCount();
        if ($pendingCppt > 0) {
            $notifications->push([
                'title' => 'CPPT menunggu verifikasi',
                'description' => $pendingCppt . ' catatan CPPT perlu ditinjau dokter.',
                'tone' => 'warning',
                'icon' => 'fa-solid fa-clipboard-check',
            ]);
        }

        $todayVisits = $this->todayVisitCount();
        if ($todayVisits > 0) {
            $notifications->push([
                'title' => 'Jadwal visite hari ini',
                'description' => $todayVisits . ' jadwal visite aktif hari ini.',
                'tone' => 'success',
                'icon' => 'fa-solid fa-calendar-day',
            ]);
        }

        $newPatients = $this->newPatientCount();
        if ($newPatients > 0) {
            $notifications->push([
                'title' => 'Pasien baru masuk',
                'description' => $newPatients . ' pasien terdaftar dalam 24 jam terakhir.',
                'tone' => 'success',
                'icon' => 'fa-solid fa-user-plus',
            ]);
        }

        return $notifications->take(5)->values();
    }

    private function pendingCpptCount(): int
    {
        if (!Schema::hasTable('cppts')) {
            return 0;
        }

        return (int) DB::table('cppts')
            ->where('verification_status', 'pending')
            ->count();
    }

    private function todayVisitCount(): int
    {
        $table = Schema::hasTable('visites') ? 'visites' : (Schema::hasTable('visit_schedules') ? 'visit_schedules' : null);
        if (! $table) {
            return 0;
        }

        $dateColumn = Schema::hasColumn($table, 'visit_date') ? 'visit_date' : 'created_at';

        return (int) DB::table($table)->whereDate($dateColumn, Carbon::today())->count();
    }

    private function newPatientCount(): int
    {
        if (!Schema::hasTable('registrations')) {
            return 0;
        }

        return (int) DB::table('registrations')
            ->where('admission_date', '>=', Carbon::today()->subDay())
            ->count();
    }

    private function activityIcon(string $action): string
    {
        return match (true) {
            str_contains($action, 'registrasi') => 'fa-solid fa-user-plus',
            str_contains($action, 'cppt') => 'fa-solid fa-notes-medical',
            str_contains($action, 'verifikasi') => 'fa-solid fa-user-doctor',
            str_contains($action, 'visite') => 'fa-solid fa-calendar-check',
            str_contains($action, 'user') => 'fa-solid fa-users-gear',
            default => 'fa-solid fa-clock-rotate-left',
        };
    }
}

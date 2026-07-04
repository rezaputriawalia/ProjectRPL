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
            'navItems' => $this->adminNavigation(),
            'stats' => [
                [
                    'label' => 'Total Perawat',
                    'value' => $roleCounts['perawat'],
                    'description' => 'Perawat aktif',
                    'icon' => 'fa-solid fa-user-nurse',
                    'accent' => 'green',
                ],
                [
                    'label' => 'Total Dokter',
                    'value' => $roleCounts['dokter'],
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
                'data' => [$roleCounts['admin'], $roleCounts['dokter'], $roleCounts['perawat']],
            ],
        ]);
    }

    private function adminNavigation(): array
    {
        return [
            ['key' => 'beranda', 'label' => 'Beranda', 'href' => route('admin.dashboard'), 'icon' => '<i class="fa-solid fa-table-cells-large"></i>'],
            ['key' => 'manajemen-user', 'label' => 'Manajemen User', 'href' => '#', 'icon' => '<i class="fa-solid fa-users-gear"></i>'],
            ['key' => 'kelola-bangsal', 'label' => 'Kelola Bangsal', 'href' => '#', 'icon' => '<i class="fa-solid fa-hospital"></i>'],
            ['key' => 'kelola-dokter', 'label' => 'Kelola Dokter', 'href' => '#', 'icon' => '<i class="fa-solid fa-user-doctor"></i>'],
            ['key' => 'kelola-perawat', 'label' => 'Kelola Perawat', 'href' => '#', 'icon' => '<i class="fa-solid fa-user-nurse"></i>'],
            ['key' => 'laporan', 'label' => 'Laporan', 'href' => '#', 'icon' => '<i class="fa-solid fa-file-lines"></i>'],
            ['key' => 'pengaturan', 'label' => 'Pengaturan', 'href' => '#', 'icon' => '<i class="fa-solid fa-gear"></i>'],
            ['key' => 'logout', 'label' => 'Logout', 'href' => '#', 'icon' => '<i class="fa-solid fa-right-from-bracket"></i>'],
        ];
    }

    private function roleCounts(): array
    {
        if (! Schema::hasTable('users') || ! Schema::hasTable('roles')) {
            return ['admin' => 0, 'dokter' => 0, 'perawat' => 0];
        }

        $rows = DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->select('roles.name', DB::raw('COUNT(users.id) as total'))
            ->whereNull('users.deleted_at')
            ->where('users.status', 'active')
            ->whereIn('roles.name', ['admin', 'dokter', 'perawat'])
            ->groupBy('roles.name')
            ->pluck('total', 'name');

        return [
            'admin' => (int) ($rows['admin'] ?? 0),
            'dokter' => (int) ($rows['dokter'] ?? 0),
            'perawat' => (int) ($rows['perawat'] ?? 0),
        ];
    }

    private function totalWards(): int
    {
        if (Schema::hasTable('wards')) {
            $query = DB::table('wards');
            if (Schema::hasColumn('wards', 'status')) {
                $query->where('status', 'active');
            }

            return (int) $query->count();
        }

        if (Schema::hasTable('registrations')) {
            return (int) DB::table('registrations')->whereNotNull('ward')->distinct()->count('ward');
        }

        return 0;
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
        if (! Schema::hasTable('registrations')) {
            return collect();
        }

        $from = $period === 'bulanan' ? Carbon::now()->startOfMonth() : Carbon::now()->startOfWeek();

        return DB::table('registrations')
            ->selectRaw('ward, COUNT(DISTINCT patient_id) as total')
            ->whereNotNull('ward')
            ->where('registration_date', '>=', $from)
            ->groupBy('ward')
            ->orderBy('ward')
            ->get()
            ->map(fn ($row) => [
                'ward' => $row->ward,
                'total' => (int) $row->total,
            ]);
    }

    private function wardCapacities(): Collection
    {
        if (Schema::hasTable('wards')) {
            $nameColumn = Schema::hasColumn('wards', 'name') ? 'name' : 'ward_name';
            $capacityColumn = Schema::hasColumn('wards', 'capacity') ? 'capacity' : null;

            if (! Schema::hasTable('registrations')) {
                return DB::table('wards')
                    ->selectRaw('wards.' . $nameColumn . ' as name')
                    ->selectRaw($capacityColumn ? 'wards.' . $capacityColumn . ' as capacity' : '1 as capacity')
                    ->orderBy('wards.' . $nameColumn)
                    ->get()
                    ->map(fn ($row) => $this->formatWardCapacity($row->name, 0, (int) $row->capacity));
            }

            return DB::table('wards')
                ->leftJoin('registrations', function ($join) use ($nameColumn) {
                    $join->on('registrations.ward', '=', 'wards.' . $nameColumn)
                        ->where('registrations.status', '=', 'active');
                })
                ->selectRaw('wards.' . $nameColumn . ' as name')
                ->selectRaw($capacityColumn ? 'MAX(wards.' . $capacityColumn . ') as capacity' : 'GREATEST(COUNT(DISTINCT registrations.patient_id), 1) as capacity')
                ->selectRaw('COUNT(DISTINCT registrations.patient_id) as occupied')
                ->groupBy('wards.' . $nameColumn)
                ->orderBy('wards.' . $nameColumn)
                ->get()
                ->map(fn ($row) => $this->formatWardCapacity($row->name, (int) $row->occupied, (int) $row->capacity));
        }

        if (! Schema::hasTable('registrations')) {
            return collect();
        }

        return DB::table('registrations')
            ->selectRaw('ward as name')
            ->selectRaw('COUNT(DISTINCT CASE WHEN status = "active" THEN patient_id END) as occupied')
            ->selectRaw('GREATEST(COUNT(DISTINCT patient_id), COUNT(DISTINCT CASE WHEN status = "active" THEN patient_id END), 1) as capacity')
            ->whereNotNull('ward')
            ->groupBy('ward')
            ->orderBy('ward')
            ->get()
            ->map(fn ($row) => $this->formatWardCapacity($row->name, (int) $row->occupied, (int) $row->capacity));
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
                'activity_logs.action',
                'activity_logs.description',
                'activity_logs.created_at',
                'users.name as user_name',
            ])
            ->orderByDesc('activity_logs.created_at')
            ->limit(8)
            ->get()
            ->map(fn ($row) => [
                'action' => $row->action,
                'description' => $row->description,
                'user_name' => $row->user_name,
                'time' => Carbon::parse($row->created_at)->diffForHumans(),
                'icon' => $this->activityIcon($row->action),
            ]);
    }

    private function systemNotifications(): Collection
    {
        if (Schema::hasTable('notifications')) {
            return DB::table('notifications')
                ->select(['type', 'data', 'created_at'])
                ->orderByDesc('created_at')
                ->limit(5)
                ->get()
                ->map(fn ($row) => [
                    'title' => str($row->type)->replace('_', ' ')->title()->toString(),
                    'description' => is_string($row->data) ? str($row->data)->limit(120)->toString() : null,
                    'tone' => 'success',
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
        $table = Schema::hasTable('cppts') ? 'cppts' : (Schema::hasTable('cppt_entries') ? 'cppt_entries' : null);

        return $table ? (int) DB::table($table)->where('status', 'submitted')->count() : 0;
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
        if (! Schema::hasTable('registrations')) {
            return 0;
        }

        return (int) DB::table('registrations')
            ->where('registration_date', '>=', Carbon::now()->subDay())
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

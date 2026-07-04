<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class NurseDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $nurse = Auth::user();
        $wards = $this->wardsForNurse($nurse?->id);
        $selectedWard = $request->string('ward')->value() ?: ($wards->first()['name'] ?? null);

        return view('perawat.dashboard', [
            'nurse' => $nurse,
            'navItems' => $this->nurseNavigation(),
            'wards' => $wards,
            'selectedWard' => $selectedWard,
            'stats' => $this->stats($selectedWard),
            'patients' => $this->patients($selectedWard),
            'activities' => $this->todayActivities($selectedWard),
            'visites' => $this->todayVisites($selectedWard),
            'reminders' => $this->reminders($selectedWard),
        ]);
    }

    private function nurseNavigation(): array
    {
        return [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => route('perawat.dashboard'), 'icon' => '<i class="fa-solid fa-table-cells-large"></i>'],
            ['key' => 'registrasi', 'label' => 'Registrasi Pasien Baru', 'href' => url('/perawat/registrasi-pasien'), 'icon' => '<i class="fa-solid fa-user-plus"></i>'],
            ['key' => 'data-pasien', 'label' => 'Data Pasien', 'href' => url('/perawat/pasien'), 'icon' => '<i class="fa-solid fa-user-magnifying-glass"></i>'],
            ['key' => 'input-cppt', 'label' => 'Input CPPT', 'href' => url('/perawat/cppt'), 'icon' => '<i class="fa-solid fa-notes-medical"></i>'],
            ['key' => 'riwayat-cppt', 'label' => 'Riwayat CPPT', 'href' => url('/perawat/riwayat-cppt'), 'icon' => '<i class="fa-solid fa-clipboard-list"></i>'],
            ['key' => 'jadwal-visite', 'label' => 'Jadwal Visite', 'href' => url('/perawat/jadwal-visite'), 'icon' => '<i class="fa-solid fa-calendar-days"></i>'],
            ['key' => 'profil', 'label' => 'Profil', 'href' => url('/perawat/profil'), 'icon' => '<i class="fa-solid fa-user"></i>'],
            ['key' => 'logout', 'label' => 'Logout', 'href' => url('/logout'), 'icon' => '<i class="fa-solid fa-right-from-bracket"></i>'],
        ];
    }

    private function wardsForNurse(?int $nurseId): Collection
    {
        if (Schema::hasTable('wards')) {
            $nameColumn = Schema::hasColumn('wards', 'name') ? 'name' : 'ward_name';

            return DB::table('wards')
                ->selectRaw($nameColumn . ' as name')
                ->when(Schema::hasColumn('wards', 'status'), fn ($query) => $query->where('status', 'active'))
                ->orderBy($nameColumn)
                ->get()
                ->map(fn ($row) => ['name' => $row->name]);
        }

        if (! Schema::hasTable('registrations')) {
            return collect();
        }

        $base = DB::table('registrations')->select('ward as name')->whereNotNull('ward')->distinct();

        if ($nurseId) {
            $nurseWard = DB::table('registrations')
                ->where('registered_by', $nurseId)
                ->whereNotNull('ward')
                ->orderByDesc('registration_date')
                ->value('ward');

            if ($nurseWard) {
                return collect([['name' => $nurseWard]])
                    ->merge($base->where('ward', '!=', $nurseWard)->orderBy('ward')->get()->map(fn ($row) => ['name' => $row->name]))
                    ->values();
            }
        }

        return $base->orderBy('ward')->get()->map(fn ($row) => ['name' => $row->name]);
    }

    private function stats(?string $ward): array
    {
        return [
            ['label' => 'Total Pasien', 'value' => $this->activePatientCount($ward), 'meta' => $ward ? 'Bangsal ' . $ward : 'Semua bangsal', 'icon' => 'fa-solid fa-bed-pulse', 'tone' => 'green'],
            ['label' => 'Pasien Baru Hari Ini', 'value' => $this->newPatientTodayCount($ward), 'meta' => 'Registrasi hari ini', 'icon' => 'fa-solid fa-user-plus', 'tone' => 'gold'],
            ['label' => 'CPPT Belum Diverifikasi', 'value' => $this->pendingCpptCount($ward), 'meta' => 'Menunggu dokter', 'icon' => 'fa-solid fa-clipboard-check', 'tone' => 'red'],
            ['label' => 'Jadwal Visite Hari Ini', 'value' => $this->todayVisitCount($ward), 'meta' => Carbon::today()->translatedFormat('d M Y'), 'icon' => 'fa-solid fa-calendar-day', 'tone' => 'green'],
        ];
    }

    private function patients(?string $ward): Collection
    {
        if (! Schema::hasTable('registrations') || ! Schema::hasTable('patients')) {
            return collect();
        }

        $cpptTable = $this->cpptTable();
        $query = DB::table('registrations')
            ->join('patients', 'patients.id', '=', 'registrations.patient_id')
            ->leftJoin('medical_records', 'medical_records.registration_id', '=', 'registrations.id')
            ->leftJoin('users as doctors', 'doctors.id', '=', 'medical_records.doctor_id')
            ->where('registrations.status', 'active')
            ->when($ward, fn ($query) => $query->where('registrations.ward', $ward))
            ->select([
                'registrations.id as registration_id',
                'registrations.ward',
                'registrations.room',
                'registrations.registration_date',
                'patients.id as patient_id',
                'patients.medical_record_number',
                'patients.name as patient_name',
                'patients.gender',
                'patients.birth_date',
                'doctors.name as doctor_name',
                'doctors.specialization as doctor_specialization',
            ]);

        if ($cpptTable) {
            $query->leftJoin($cpptTable . ' as cppt', 'cppt.registration_id', '=', 'registrations.id')
                ->selectRaw('MAX(CASE WHEN cppt.status = "rejected" THEN 1 ELSE 0 END) as has_rejected_cppt')
                ->selectRaw('MAX(CASE WHEN cppt.status = "submitted" THEN 1 ELSE 0 END) as has_pending_cppt')
                ->selectRaw('MAX(CASE WHEN DATE(' . $this->cpptDateColumn($cpptTable, 'cppt') . ') = ? THEN 1 ELSE 0 END) as has_cppt_today', [Carbon::today()->toDateString()]);
        } else {
            $query->selectRaw('0 as has_rejected_cppt')->selectRaw('0 as has_pending_cppt')->selectRaw('0 as has_cppt_today');
        }

        return $query
            ->groupBy(['registrations.id', 'registrations.ward', 'registrations.room', 'registrations.registration_date', 'patients.id', 'patients.medical_record_number', 'patients.name', 'patients.gender', 'patients.birth_date', 'doctors.name', 'doctors.specialization'])
            ->orderBy('patients.name')
            ->get()
            ->map(fn ($row) => [
                'registration_id' => $row->registration_id,
                'patient_id' => $row->patient_id,
                'medical_record_number' => $row->medical_record_number,
                'patient_name' => $row->patient_name,
                'gender' => $row->gender === 'P' ? 'Perempuan' : 'Laki-laki',
                'age' => $row->birth_date ? Carbon::parse($row->birth_date)->age : null,
                'ward' => $row->ward,
                'room' => $row->room,
                'doctor' => trim(($row->doctor_name ?? '-') . ($row->doctor_specialization ? ', ' . $row->doctor_specialization : '')),
                'status' => $this->patientStatus((int) $row->has_rejected_cppt, (int) $row->has_pending_cppt, (int) $row->has_cppt_today),
                'detail_url' => url('/perawat/pasien/' . $row->patient_id),
                'cppt_url' => url('/perawat/cppt/create?registration_id=' . $row->registration_id),
                'history_url' => url('/perawat/pasien/' . $row->patient_id . '/riwayat-cppt'),
            ]);
    }

    private function todayActivities(?string $ward): Collection
    {
        if (Schema::hasTable('activity_logs')) {
            $activities = DB::table('activity_logs')
                ->leftJoin('users', 'users.id', '=', 'activity_logs.user_id')
                ->select(['activity_logs.action', 'activity_logs.description', 'activity_logs.created_at', 'users.name as user_name'])
                ->whereDate('activity_logs.created_at', Carbon::today())
                ->orderByDesc('activity_logs.created_at')
                ->limit(8)
                ->get()
                ->map(fn ($row) => $this->activityRow($row->action, $row->description, $row->created_at, $row->user_name));

            if ($activities->isNotEmpty()) {
                return $activities;
            }
        }

        return collect()->merge($this->registrationActivities($ward))->merge($this->cpptActivities($ward))->merge($this->visitActivities($ward))->sortByDesc('datetime')->take(8)->values();
    }

    private function todayVisites(?string $ward): Collection
    {
        $table = $this->visitTable();
        if (! $table) {
            return collect();
        }

        $dateColumn = Schema::hasColumn($table, 'visit_date') ? 'visit_date' : 'created_at';
        $doctorColumn = Schema::hasColumn($table, 'doctor_id') ? 'doctor_id' : 'user_id';

        return DB::table($table)
            ->leftJoin('users as doctors', 'doctors.id', '=', $table . '.' . $doctorColumn)
            ->leftJoin('registrations', 'registrations.id', '=', $table . '.registration_id')
            ->selectRaw($table . '.start_time as start_time')
            ->selectRaw('doctors.name as doctor_name')
            ->selectRaw('registrations.ward as ward')
            ->whereDate($table . '.' . $dateColumn, Carbon::today())
            ->when($ward, fn ($query) => $query->where('registrations.ward', $ward))
            ->orderBy($table . '.start_time')
            ->limit(6)
            ->get()
            ->map(fn ($row) => ['time' => $row->start_time ? Carbon::parse($row->start_time)->format('H:i') : '-', 'doctor' => $row->doctor_name ?? '-', 'ward' => $row->ward ?? '-']);
    }

    private function reminders(?string $ward): array
    {
        return [
            ['title' => 'Pasien belum memiliki CPPT hari ini', 'value' => $this->patientsWithoutCpptToday($ward), 'description' => 'Perlu dilengkapi sebelum akhir shift.', 'tone' => 'warning', 'icon' => 'fa-solid fa-notes-medical'],
            ['title' => 'Pasien belum divisite', 'value' => $this->patientsWithoutVisitToday($ward), 'description' => 'Koordinasikan dengan dokter DPJP.', 'tone' => 'green', 'icon' => 'fa-solid fa-calendar-xmark'],
            ['title' => 'CPPT ditolak dokter', 'value' => $this->rejectedCpptCount($ward), 'description' => 'Segera revisi catatan yang dikembalikan.', 'tone' => 'danger', 'icon' => 'fa-solid fa-triangle-exclamation'],
        ];
    }

    private function activePatientCount(?string $ward): int
    {
        if (! Schema::hasTable('registrations')) {
            return 0;
        }

        return (int) DB::table('registrations')->where('status', 'active')->when($ward, fn ($query) => $query->where('ward', $ward))->distinct()->count('patient_id');
    }

    private function newPatientTodayCount(?string $ward): int
    {
        if (! Schema::hasTable('registrations')) {
            return 0;
        }

        return (int) DB::table('registrations')->whereDate('registration_date', Carbon::today())->when($ward, fn ($query) => $query->where('ward', $ward))->count();
    }

    private function pendingCpptCount(?string $ward): int
    {
        $table = $this->cpptTable();
        if (! $table) {
            return 0;
        }

        return (int) DB::table($table)->leftJoin('registrations', 'registrations.id', '=', $table . '.registration_id')->where($table . '.status', 'submitted')->when($ward, fn ($query) => $query->where('registrations.ward', $ward))->count();
    }

    private function todayVisitCount(?string $ward): int
    {
        return $this->todayVisites($ward)->count();
    }

    private function patientsWithoutCpptToday(?string $ward): int
    {
        $table = $this->cpptTable();
        if (! $table || ! Schema::hasTable('registrations')) {
            return 0;
        }

        return (int) DB::table('registrations')
            ->where('registrations.status', 'active')
            ->when($ward, fn ($query) => $query->where('registrations.ward', $ward))
            ->whereNotExists(function ($query) use ($table) {
                $query->selectRaw('1')->from($table)->whereColumn($table . '.registration_id', 'registrations.id')->whereDate($this->cpptDateColumn($table), Carbon::today());
            })
            ->count();
    }

    private function patientsWithoutVisitToday(?string $ward): int
    {
        $table = $this->visitTable();
        if (! $table || ! Schema::hasTable('registrations')) {
            return 0;
        }

        $dateColumn = Schema::hasColumn($table, 'visit_date') ? 'visit_date' : 'created_at';

        return (int) DB::table('registrations')
            ->where('registrations.status', 'active')
            ->when($ward, fn ($query) => $query->where('registrations.ward', $ward))
            ->whereNotExists(function ($query) use ($table, $dateColumn) {
                $query->selectRaw('1')->from($table)->whereColumn($table . '.registration_id', 'registrations.id')->whereDate($table . '.' . $dateColumn, Carbon::today());
            })
            ->count();
    }

    private function rejectedCpptCount(?string $ward): int
    {
        $table = $this->cpptTable();
        if (! $table) {
            return 0;
        }

        return (int) DB::table($table)->leftJoin('registrations', 'registrations.id', '=', $table . '.registration_id')->where($table . '.status', 'rejected')->when($ward, fn ($query) => $query->where('registrations.ward', $ward))->count();
    }

    private function registrationActivities(?string $ward): Collection
    {
        if (! Schema::hasTable('registrations')) {
            return collect();
        }

        return DB::table('registrations')
            ->leftJoin('patients', 'patients.id', '=', 'registrations.patient_id')
            ->select(['registrations.registration_date as created_at', 'patients.name as patient_name'])
            ->whereDate('registrations.registration_date', Carbon::today())
            ->when($ward, fn ($query) => $query->where('registrations.ward', $ward))
            ->limit(5)
            ->get()
            ->map(fn ($row) => $this->activityRow('Registrasi Pasien', 'Registrasi pasien ' . $row->patient_name, $row->created_at, null));
    }

    private function cpptActivities(?string $ward): Collection
    {
        $table = $this->cpptTable();
        if (! $table) {
            return collect();
        }

        $dateColumn = $this->cpptDateColumn($table);

        return DB::table($table)
            ->leftJoin('registrations', 'registrations.id', '=', $table . '.registration_id')
            ->leftJoin('patients', 'patients.id', '=', $table . '.patient_id')
            ->selectRaw($table . '.' . $dateColumn . ' as created_at')
            ->selectRaw('patients.name as patient_name')
            ->whereDate($table . '.' . $dateColumn, Carbon::today())
            ->when($ward, fn ($query) => $query->where('registrations.ward', $ward))
            ->limit(5)
            ->get()
            ->map(fn ($row) => $this->activityRow('Input CPPT', 'Input CPPT pasien ' . $row->patient_name, $row->created_at, null));
    }

    private function visitActivities(?string $ward): Collection
    {
        return $this->todayVisites($ward)->map(fn ($row) => ['time' => $row['time'], 'datetime' => Carbon::today()->setTimeFromTimeString($row['time'] === '-' ? '00:00' : $row['time']), 'title' => 'Monitoring Pasien', 'description' => 'Jadwal visite ' . $row['doctor'] . ' di ' . $row['ward'], 'user' => null, 'icon' => 'fa-solid fa-stethoscope']);
    }

    private function activityRow(string $action, ?string $description, mixed $createdAt, ?string $userName): array
    {
        $date = Carbon::parse($createdAt);

        return ['time' => $date->format('H:i'), 'datetime' => $date, 'title' => $action, 'description' => $description, 'user' => $userName, 'icon' => $this->activityIcon($action)];
    }

    private function patientStatus(int $hasRejected, int $hasPending, int $hasCpptToday): array
    {
        if ($hasRejected) {
            return ['label' => 'Revisi CPPT', 'tone' => 'danger'];
        }

        if ($hasPending) {
            return ['label' => 'Menunggu Verifikasi', 'tone' => 'warning'];
        }

        if ($hasCpptToday) {
            return ['label' => 'Termonitor', 'tone' => 'success'];
        }

        return ['label' => 'Belum CPPT', 'tone' => 'danger'];
    }

    private function activityIcon(string $action): string
    {
        $action = strtolower($action);

        return match (true) {
            str_contains($action, 'cppt') => 'fa-solid fa-notes-medical',
            str_contains($action, 'registrasi') => 'fa-solid fa-user-plus',
            str_contains($action, 'monitoring') => 'fa-solid fa-heart-pulse',
            str_contains($action, 'visite') => 'fa-solid fa-calendar-check',
            default => 'fa-solid fa-clock-rotate-left',
        };
    }

    private function cpptTable(): ?string
    {
        return Schema::hasTable('cppts') ? 'cppts' : (Schema::hasTable('cppt_entries') ? 'cppt_entries' : null);
    }

    private function visitTable(): ?string
    {
        return Schema::hasTable('visites') ? 'visites' : (Schema::hasTable('visit_schedules') ? 'visit_schedules' : null);
    }

    private function cpptDateColumn(string $table, ?string $alias = null): string
    {
        $column = Schema::hasColumn($table, 'entry_datetime') ? 'entry_datetime' : 'created_at';

        return $alias ? $alias . '.' . $column : $column;
    }
}

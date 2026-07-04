<?php

namespace Database\Seeders;

use App\Models\CpptEntry;
use App\Models\CpptVerification;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Registration;
use App\Models\Role;
use App\Models\User;
use App\Models\VisitSchedule;
use Illuminate\Database\Seeder;

class ClinicalDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->whereHas('role', fn ($query) => $query->where('name', 'admin'))->first();
        $dokterRoleId = Role::query()->where('name', 'dokter')->value('id');
        $perawatRoleId = Role::query()->where('name', 'perawat')->value('id');

        $dokters = User::query()->where('role_id', $dokterRoleId)->get();
        $perawats = User::query()->where('role_id', $perawatRoleId)->get();
        $patients = Patient::query()->get();
        $wards = WardData::names();

        foreach ($patients as $index => $patient) {
            $registeredBy = $perawats->random();
            $registrationDate = fake('id_ID')->dateTimeBetween('-60 days', 'now');
            $status = fake('id_ID')->randomElement(['active', 'active', 'active', 'discharged']);

            $registration = Registration::query()->create([
                'registration_number' => 'REG-' . now()->format('ymd') . '-' . str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT),
                'patient_id' => $patient->id,
                'registered_by' => $registeredBy->id,
                'registration_date' => $registrationDate,
                'care_type' => fake('id_ID')->randomElement(['rawat_jalan', 'rawat_inap', 'igd']),
                'ward' => $wards[$index % count($wards)],
                'room' => 'R-' . str_pad((string) fake('id_ID')->numberBetween(1, 40), 2, '0', STR_PAD_LEFT),
                'bed' => (string) fake('id_ID')->numberBetween(1, 6),
                'main_complaint' => fake('id_ID')->randomElement([
                    'Demam dan nyeri kepala sejak dua hari',
                    'Sesak napas memberat saat aktivitas',
                    'Nyeri perut kanan bawah',
                    'Kontrol tekanan darah tidak stabil',
                    'Mual, muntah, dan lemas',
                    'Luka pasca operasi membutuhkan observasi',
                ]),
                'status' => $status,
                'discharged_at' => $status === 'discharged'
                    ? fake('id_ID')->dateTimeBetween($registrationDate, 'now')
                    : null,
            ]);

            MedicalRecord::factory()->create([
                'patient_id' => $patient->id,
                'registration_id' => $registration->id,
                'doctor_id' => $dokters->random()->id,
                'status' => $status === 'discharged' ? 'closed' : 'open',
            ]);
        }

        $medicalRecords = MedicalRecord::query()->with('registration')->get();

        for ($i = 1; $i <= 100; $i++) {
            $medicalRecord = $medicalRecords->random();
            $isDoctorNote = fake('id_ID')->boolean(45);
            $creator = $isDoctorNote ? $dokters->random() : $perawats->random();
            $status = fake('id_ID')->randomElement(['submitted', 'verified', 'verified', 'draft', 'rejected']);

            $cppt = CpptEntry::factory()->create([
                'patient_id' => $medicalRecord->patient_id,
                'registration_id' => $medicalRecord->registration_id,
                'medical_record_id' => $medicalRecord->id,
                'created_by' => $creator->id,
                'profession' => $isDoctorNote ? 'dokter' : 'perawat',
                'status' => $status,
            ]);

            if (in_array($status, ['verified', 'rejected'], true)) {
                CpptVerification::factory()->create([
                    'cppt_entry_id' => $cppt->id,
                    'verified_by' => $dokters->random()->id,
                    'verification_status' => $status,
                ]);
            }
        }

        $activeRegistrations = Registration::query()->where('status', 'active')->get();

        for ($i = 1; $i <= 50; $i++) {
            $registration = $activeRegistrations->isNotEmpty()
                ? $activeRegistrations->random()
                : Registration::query()->inRandomOrder()->first();

            VisitSchedule::factory()->create([
                'patient_id' => $registration->patient_id,
                'registration_id' => $registration->id,
                'doctor_id' => $dokters->random()->id,
                'created_by' => fake('id_ID')->boolean(70) ? $admin?->id : $perawats->random()->id,
                'title' => 'Visite ' . $registration->ward,
            ]);
        }
    }
}

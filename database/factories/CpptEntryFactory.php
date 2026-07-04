<?php

namespace Database\Factories;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CpptEntry>
 */
class CpptEntryFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('id_ID');

        return [
            'patient_id' => Patient::query()->inRandomOrder()->value('id') ?? Patient::factory(),
            'registration_id' => Registration::query()->inRandomOrder()->value('id') ?? Registration::factory(),
            'medical_record_id' => MedicalRecord::query()->inRandomOrder()->value('id') ?? MedicalRecord::factory(),
            'created_by' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'profession' => $faker->randomElement(['dokter', 'perawat']),
            'entry_datetime' => $faker->dateTimeBetween('-30 days', 'now'),
            'subjective' => $faker->randomElement([
                'Pasien mengeluh nyeri berkurang, masih terasa lemas.',
                'Pasien mengatakan tidur cukup dan nafsu makan membaik.',
                'Pasien masih mengeluh sesak saat berjalan ke kamar mandi.',
                'Pasien mengeluh mual namun tidak muntah.',
            ]),
            'objective' => $faker->randomElement([
                'Tekanan darah 130/80 mmHg, nadi 86 x/menit, suhu 36.8 C.',
                'Saturasi 96 persen dengan nasal kanul 2 lpm, napas reguler.',
                'Luka operasi bersih, tidak tampak rembesan, nyeri tekan minimal.',
                'Mukosa lembab, diuresis cukup, abdomen supel.',
            ]),
            'assessment' => $faker->randomElement([
                'Kondisi stabil, keluhan utama membaik.',
                'Masalah nyeri masih perlu pemantauan.',
                'Risiko infeksi terkontrol dengan terapi berjalan.',
                'Status hidrasi membaik.',
            ]),
            'planning' => $faker->randomElement([
                'Lanjutkan terapi, monitor tanda vital tiap shift.',
                'Evaluasi nyeri dan respons obat, edukasi mobilisasi bertahap.',
                'Pantau intake-output, lanjutkan cairan sesuai instruksi.',
                'Kolaborasi pemeriksaan laboratorium ulang besok pagi.',
            ]),
            'instruction' => $faker->optional(0.7)->randomElement([
                'Laporkan bila suhu di atas 38 C.',
                'Pantau saturasi dan keluhan sesak.',
                'Edukasi keluarga terkait pembatasan cairan.',
                'Jadwalkan visite ulang besok.',
            ]),
            'status' => $faker->randomElement(['draft', 'submitted', 'verified', 'rejected']),
        ];
    }
}

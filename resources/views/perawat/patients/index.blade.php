<x-layouts.app title="Data Pasien" role="perawat" brand="SIGAP Perawat" subtitle="Rumah Sakit Jiwa" active="patients"
    :nav-items="$navItems" :userName="auth()->user()->name" userRole="Perawat">

    <div class="admin-dashboard">

        <div class="admin-dashboard__header d-flex justify-content-between align-items-center">

            <div>

                <h1>Data Pasien Bangsal</h1>

                <p>
                    Menampilkan seluruh pasien aktif pada bangsal yang Anda tangani.
                </p>

            </div>

            <a href="{{ route('perawat.patients.create') }}" class="sigap-button sigap-button--primary sigap-button--md">

                <i class="fa-solid fa-plus"></i>

                Registrasi Pasien

            </a>

        </div>

        <section class="admin-panel">

            <div class="sigap-table-card">

                <div class="sigap-table-card__scroll">

                    <table class="sigap-table">

                        <thead>

                            <tr>

                                <th>No RM</th>

                                <th>Nama Pasien</th>

                                <th>Dokter</th>

                                <th>Ruangan</th>

                                <th>Status</th>

                                <th width="260">

                                    Aksi

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($registrations as $registration)
                                <tr>

                                    <td>

                                        {{ $registration->patient->medical_record_number }}

                                    </td>

                                    <td>

                                        <strong>

                                            {{ $registration->patient->name }}

                                        </strong>

                                    </td>

                                    <td>

                                        {{ $registration->doctor->name }}

                                    </td>

                                    <td>

                                        {{ $registration->room->name }}

                                    </td>

                                    <td>

                                        @if ($registration->status == 'active')
                                            <span class="sigap-badge sigap-badge--success">

                                                Aktif

                                            </span>
                                        @else
                                            <span class="sigap-badge sigap-badge--danger">

                                                Selesai

                                            </span>
                                        @endif

                                    </td>

                                    <td style="width:230px">

                                        <div class="sigap-action-stack">

                                            <a href="{{ route('perawat.patients.monitorings.create', $registration->patient) }}"
                                                class="sigap-button sigap-button--primary sigap-button--sm">

                                                <i class="fa-solid fa-notes-medical"></i>
                                                Monitoring Tindakan

                                            </a>

                                            <a href="{{ route('perawat.patients.cppts.index', $registration->patient) }}"
                                                class="sigap-button sigap-button--secondary sigap-button--sm">

                                                <i class="fa-solid fa-file-medical"></i>
                                                Riwayat CPPT

                                            </a>

                                            <a href="{{ route('perawat.patients.edit', $registration->patient) }}"
                                                class="sigap-button sigap-button--brown sigap-button--sm">

                                                <i class="fa-solid fa-pen"></i>
                                                Edit

                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" style="text-align:center;padding:50px;">

                                        Belum ada data pasien.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

    </div>

    @push('scripts')

        @if (session('success'))
            <script>
                Swal.fire({

                    icon: 'success',

                    title: 'Berhasil',

                    text: "{{ session('success') }}",

                    timer: 1800,

                    showConfirmButton: false

                });
            </script>
        @endif

        @if (session('monitoring_success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    Swal.fire({

                        icon: 'success',

                        title: 'Monitoring Berhasil',

                        html: `
Monitoring tindakan untuk
<b>{{ session('patient_name') }}</b>
berhasil disimpan.<br><br>
Lanjutkan membuat CPPT?
`,

                        showCancelButton: true,

                        confirmButtonColor: '#4A835F',

                        cancelButtonColor: '#C7352E',

                        confirmButtonText: 'Lanjut ke CPPT',

                        cancelButtonText: 'Nanti'

                    }).then(result => {

                        if (result.isConfirmed) {

                            window.location.href =
                                "{{ route('perawat.patients.cppts.create', session('patient_id')) }}";

                        }

                    });

                });
            </script>
        @endif

    @endpush

</x-layouts.app>

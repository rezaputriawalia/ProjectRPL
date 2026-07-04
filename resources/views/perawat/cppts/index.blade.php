<x-layouts.app
    title="Riwayat CPPT"
    role="perawat"
    brand="SIGAP Perawat"
    subtitle="Rumah Sakit Jiwa"
    active="patients"
    :nav-items="$navItems"
    :userName="auth()->user()->name"
    userRole="Perawat">

<div class="admin-dashboard">

    <div class="admin-dashboard__header d-flex justify-content-between align-items-center">

        <div>

            <h1>Riwayat CPPT</h1>

            <p>

                Pasien
                <strong>{{ $patient->name }}</strong>
                ({{ $patient->medical_record_number }})

            </p>

            <small class="text-muted">

                Dokter Penanggung Jawab :
                <strong>{{ $registration->doctor->name }}</strong>

            </small>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('perawat.patients.index') }}"
                class="sigap-button sigap-button--secondary sigap-button--md">

                <i class="fa-solid fa-arrow-left"></i>

                Kembali

            </a>

            <a
                href="{{ route('perawat.patients.cppts.create',$patient) }}"
                class="sigap-button sigap-button--primary sigap-button--md">

                <i class="fa-solid fa-plus"></i>

                Tambah CPPT

            </a>

        </div>

    </div>

    <section class="admin-panel">

        <div class="sigap-table-card">

            <div class="sigap-table-card__scroll">

                <table class="sigap-table">

                    <thead>

                        <tr>

                            <th>Tanggal</th>

                            <th>Perawat</th>

                            <th>Status</th>

                            <th width="130">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($cppts as $cppt)

                            <tr>

                                <td>

                                    {{ $cppt->created_at->format('d M Y, H:i') }}

                                </td>

                                <td>

                                    {{ $cppt->nurse?->name ?? '-' }}

                                </td>

                                <td>

                                    @if($cppt->verification_status=='verified')

                                        <span class="sigap-badge sigap-badge--success">

                                            Verified

                                        </span>

                                    @else

                                        <span class="sigap-badge sigap-badge--warning">

                                            Pending

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <a
                                        href="{{ route('perawat.patients.cppts.show',[$patient,$cppt]) }}"
                                        class="sigap-button sigap-button--secondary sigap-button--sm">

                                        <i class="fa-solid fa-eye"></i>

                                        Detail

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="text-center py-5">

                                    Belum ada data CPPT.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>

</div>

@if(session('success'))

<script>

Swal.fire({

    icon:'success',

    title:'Berhasil',

    text:"{{ session('success') }}",

    timer:1800,

    showConfirmButton:false

});

</script>

@endif

</x-layouts.app>
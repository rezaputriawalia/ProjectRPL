<x-layouts.app
    title="Dashboard Dokter"
    role="doctor"
    brand="SIGAP Dokter"
    subtitle="Rumah Sakit Jiwa"
    active="dashboard"
    :nav-items="$navItems"
    :userName="auth()->user()->name"
    userRole="Dokter">

<div class="admin-dashboard">

    <div class="admin-dashboard__header">

        <div>

            <h1>Dashboard Dokter</h1>

            <p>

                Selamat datang,
                <strong>{{ auth()->user()->name }}</strong>

            </p>

        </div>

    </div>

    <div class="row g-4">

        <div class="col-lg-4">

            <div class="admin-stat-card">

                <div class="admin-stat-card__icon admin-stat-card__icon--warning">

                    <i class="fa-solid fa-clock"></i>

                </div>

                <div>

                    <small>Pending Verifikasi</small>

                    <h2>{{ $pending }}</h2>

                    <span>Menunggu persetujuan dokter</span>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="admin-stat-card">

                <div class="admin-stat-card__icon admin-stat-card__icon--success">

                    <i class="fa-solid fa-circle-check"></i>

                </div>

                <div>

                    <small>Sudah Diverifikasi</small>

                    <h2>{{ $verified }}</h2>

                    <span>CPPT telah diverifikasi</span>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="admin-stat-card">

                <div class="admin-stat-card__icon admin-stat-card__icon--primary">

                    <i class="fa-solid fa-file-medical"></i>

                </div>

                <div>

                    <small>CPPT Hari Ini</small>

                    <h2>{{ $today }}</h2>

                    <span>Input CPPT hari ini</span>

                </div>

            </div>

        </div>

    </div>

    <section class="admin-panel mt-4">

        <div class="admin-panel__header">

            <div>

                <h2>Daftar Pasien Tanggung Jawab</h2>

                <p>

                    Menampilkan seluruh pasien aktif yang menjadi tanggung jawab dokter.

                </p>

            </div>

        </div>

        <div class="sigap-table-card mt-4">

            <div class="table-responsive">

                <table class="table sigap-table align-middle mb-0">

                    <thead>

                        <tr>

                            <th>No. RM</th>

                            <th>Nama Pasien</th>

                            <th>Ruangan</th>

                            <th>Status CPPT Hari Ini</th>

                            <th class="text-center" width="150">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($patients as $registration)

                            @php

                                $todayCppt = $registration->cppts
                                    ->where('created_at', '>=', now()->startOfDay())
                                    ->sortByDesc('created_at')
                                    ->first();

                            @endphp

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

                                    {{ $registration->room->name }}

                                </td>

                                <td>

                                    @if(!$todayCppt)

                                        <span class="sigap-badge sigap-badge--danger">

                                            Belum Input

                                        </span>

                                    @elseif($todayCppt->verification_status == 'pending')

                                        <span class="sigap-badge sigap-badge--warning">

                                            Pending

                                        </span>

                                    @else

                                        <span class="sigap-badge sigap-badge--success">

                                            Verified

                                        </span>

                                    @endif

                                </td>

                                <td class="text-center">

                                    @if($todayCppt)

                                        <a
                                            href="{{ route('doctor.cppts.show',$todayCppt) }}"
                                            class="sigap-button sigap-button--primary sigap-button--sm">

                                            <i class="fa-solid fa-eye"></i>

                                            Detail

                                        </a>

                                    @else

                                        <span class="text-muted">

                                            -

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center py-5 text-muted">

                                    <i class="fa-solid fa-bed fa-2x mb-3"></i>

                                    <br>

                                    Belum ada pasien aktif.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>

</div>

</x-layouts.app>
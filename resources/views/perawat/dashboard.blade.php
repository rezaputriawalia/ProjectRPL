<x-layouts.app
    title="Dashboard Perawat"
    role="perawat"
    brand="SIGAP Perawat"
    subtitle="Rumah Sakit Jiwa"
    active="dashboard"
    :nav-items="$navItems"
    :userName="auth()->user()->name"
    userRole="Perawat">

<div class="admin-dashboard">

    <div class="admin-dashboard__header">

        <div>

            <h1>Dashboard Perawat</h1>

            <p>
                Selamat datang kembali,
                <strong>{{ auth()->user()->name }}</strong>.
                Berikut ringkasan kondisi bangsal yang Anda tangani.
            </p>

        </div>

    </div>

    <div class="row g-4">

        <div class="col-md-3">

            <section class="admin-panel">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted fw-semibold">
                            TOTAL PASIEN AKTIF
                        </small>

                        <h2 class="mt-2 mb-1">
                            {{ $totalPatients }}
                        </h2>

                        <small class="text-success">
                            Pasien aktif
                        </small>

                    </div>

                    <div class="admin-stat-card__icon admin-stat-card__icon--green">

                        <i class="fa-solid fa-bed"></i>

                    </div>

                </div>

            </section>

        </div>

        <div class="col-md-3">

            <section class="admin-panel">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted fw-semibold">
                            RAWAT INAP
                        </small>

                        <h2 class="mt-2 mb-1">
                            {{ $rawatInap }}
                        </h2>

                        <small class="text-success">
                            Sedang dirawat
                        </small>

                    </div>

                    <div class="admin-stat-card__icon admin-stat-card__icon--green">

                        <i class="fa-solid fa-hospital-user"></i>

                    </div>

                </div>

            </section>

        </div>

        <div class="col-md-3">

            <section class="admin-panel">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted fw-semibold">
                            RAWAT JALAN
                        </small>

                        <h2 class="mt-2 mb-1">
                            {{ $rawatJalan }}
                        </h2>

                        <small class="text-warning">
                            Pasien kontrol
                        </small>

                    </div>

                    <div class="admin-stat-card__icon admin-stat-card__icon--gold">

                        <i class="fa-solid fa-user-doctor"></i>

                    </div>

                </div>

            </section>

        </div>

        <div class="col-md-3">

            <section class="admin-panel">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted fw-semibold">
                            RUANGAN
                        </small>

                        <h2 class="mt-2 mb-1">
                            {{ $totalRooms }}
                        </h2>

                        <small class="text-danger">
                            Bangsal Anda
                        </small>

                    </div>

                    <div class="admin-stat-card__icon admin-stat-card__icon--brown">

                        <i class="fa-solid fa-door-open"></i>

                    </div>

                </div>

            </section>

        </div>

    </div>

    <div class="row mt-4">

        <div class="col-12">

            <section class="admin-panel">

                <div class="admin-panel__header admin-panel__header--plain">

                    <div>

                        <h2>Pasien Terbaru</h2>

                        <p>
                            Daftar pasien aktif pada bangsal Anda.
                        </p>

                    </div>

                </div>

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

                                            <span class="sigap-badge sigap-badge--success">

                                                Aktif

                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5" style="text-align:center;padding:45px;">

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

    </div>

</div>

</x-layouts.app>
<x-layouts.app
    title="Data Pasien"
    role="admin"
    brand="SIGAP Admin"
    subtitle="Rumah Sakit Jiwa"
    active="patients"
    :nav-items="$navItems"
    :userName="auth()->user()->name"
    userRole="Administrator">

<div class="admin-dashboard">

    <div class="admin-dashboard__header">

        <div>

            <h1>Data Pasien</h1>

            <p>
                Monitoring seluruh data pasien Rumah Sakit Jiwa.
            </p>

        </div>

    </div>

    <section class="admin-panel">

        <div class="sigap-table-card">

            <div class="sigap-table-card__scroll">

                <table class="sigap-table">

                    <thead>

                        <tr>

                            <th>No RM</th>
                            <th>Nama Pasien</th>
                            <th>Jenis Kelamin</th>
                            <th>Dokter</th>
                            <th>Bangsal</th>
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

                                    <strong>{{ $registration->patient->name }}</strong>

                                </td>

                                <td>

                                    {{ $registration->patient->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}

                                </td>

                                <td>

                                    {{ $registration->doctor?->name ?? '-' }}

                                </td>

                                <td>

                                    {{ $registration->room?->ward?->name ?? '-' }}

                                </td>

                                <td>

                                    {{ $registration->room?->name ?? '-' }}

                                </td>

                                <td>

                                    @if($registration->status == 'active')

                                        <span class="sigap-badge sigap-badge--success">

                                            Aktif

                                        </span>

                                    @else

                                        <span class="sigap-badge sigap-badge--danger">

                                            Selesai

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" style="text-align:center;padding:40px">

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

</x-layouts.app>
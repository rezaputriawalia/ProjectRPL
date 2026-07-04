<x-layouts.app
    title="Daftar CPPT"
    role="doctor"
    brand="SIGAP Dokter"
    subtitle="Rumah Sakit Jiwa"
    active="cppts"
    :nav-items="$navItems"
    :userName="auth()->user()->name"
    userRole="Dokter">

<div class="admin-dashboard">

    <div class="admin-dashboard__header d-flex justify-content-between align-items-center">

        <div>

            <h1>Daftar CPPT</h1>

            <p>
                Daftar CPPT pasien yang menunggu verifikasi dokter.
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

                            <th>Perawat</th>

                            <th>Tanggal</th>

                            <th>Status</th>

                            <th width="140">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($cppts as $cppt)

                        <tr>

                            <td>

                                {{ $cppt->registration->patient->medical_record_number }}

                            </td>

                            <td>

                                <strong>

                                    {{ $cppt->registration->patient->name }}

                                </strong>

                            </td>

                            <td>

                                {{ $cppt->nurse?->name ?? '-' }}

                            </td>

                            <td>

                                {{ $cppt->created_at->format('d M Y • H:i') }}

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
                                    href="{{ route('doctor.cppts.show',$cppt) }}"
                                    class="sigap-button sigap-button--primary sigap-button--sm">

                                    Detail

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" style="text-align:center;padding:50px">

                                Belum ada CPPT yang menunggu verifikasi.

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

document.addEventListener('DOMContentLoaded',function(){

Swal.fire({

icon:'success',

title:'Berhasil',

text:"{{ session('success') }}",

timer:1800,

showConfirmButton:false

});

});

</script>

@endif

</x-layouts.app>
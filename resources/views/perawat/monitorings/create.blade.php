<x-layouts.app
    title="Monitoring Tindakan"
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

            <h1>Monitoring Tindakan</h1>

            <p>

                Pasien
                <strong>{{ $patient->name }}</strong>
                ({{ $patient->medical_record_number }})

            </p>

        </div>

        <a
            href="{{ route('perawat.patients.index') }}"
            class="sigap-button sigap-button--secondary sigap-button--md">

            <i class="fa-solid fa-arrow-left"></i>

            Kembali

        </a>

    </div>

    <section class="admin-panel">

        <form
            method="POST"
            action="{{ route('perawat.patients.monitorings.store',$patient) }}">

            @csrf

            <div class="mb-5">

                <label class="sigap-label">

                    Terapi Aktivitas Kelompok (TAK)

                </label>

                <small class="text-muted d-block mb-3">

                    Satu baris mewakili satu tindakan.

                </small>

                <textarea
                    name="tak"
                    rows="8"
                    class="form-control sigap-form-control"
                    placeholder="Contoh:

Terapi Musik
Sosialisasi
Orientasi Realita">@if($monitoring){{ $monitoring->items->where('category','TAK')->pluck('action')->implode("\n") }}@endif</textarea>

            </div>

            <div class="mb-4">

                <label class="sigap-label">

                    Activity Daily Living (ADL)

                </label>

                <small class="text-muted d-block mb-3">

                    Satu baris mewakili satu tindakan.

                </small>

                <textarea
                    name="adl"
                    rows="8"
                    class="form-control sigap-form-control"
                    placeholder="Contoh:

Membantu mandi
Membantu makan
Mengganti pakaian">@if($monitoring){{ $monitoring->items->where('category','ADL')->pluck('action')->implode("\n") }}@endif</textarea>

            </div>

            <div class="d-flex justify-content-end gap-3 mt-5">

                <a
                    href="{{ route('perawat.patients.index') }}"
                    class="sigap-button sigap-button--secondary sigap-button--md">

                    Batal

                </a>

                <button
                    type="submit"
                    class="sigap-button sigap-button--primary sigap-button--md">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Simpan Monitoring

                </button>

            </div>

        </form>

    </section>

</div>

@push('scripts')

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

@endpush

</x-layouts.app>
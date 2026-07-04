<x-layouts.app title="Detail CPPT" role="perawat" brand="SIGAP Perawat" subtitle="Rumah Sakit Jiwa" active="patients"
    :nav-items="$navItems" :userName="auth()->user()->name" userRole="Perawat">

    <div class="admin-dashboard">

        <div class="admin-dashboard__header d-flex justify-content-between align-items-center">

            <div>

                <h1>Detail CPPT</h1>

                <p>

                    {{ $patient->name }}

                    ({{ $patient->medical_record_number }})

                </p>

            </div>

            <a href="{{ route('perawat.patients.cppts.index', $patient) }}"
                class="sigap-button sigap-button--secondary sigap-button--md">

                <i class="fa-solid fa-arrow-left"></i>

                Kembali

            </a>

        </div>

        <section class="admin-panel">

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Dokter Penanggung Jawab

                    </label>

                    <div class="form-control">

                        {{ $registration->doctor->name }}

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Perawat

                    </label>

                    <div class="form-control">

                        {{ $cppt->nurse?->name ?? '-' }}

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Ruangan

                    </label>

                    <div class="form-control">

                        {{ $registration->room->name }}

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Status Verifikasi

                    </label>

                    <div>

                        @if ($cppt->verification_status == 'verified')
                            <span class="sigap-badge sigap-badge--success">

                                Verified

                            </span>
                        @else
                            <span class="sigap-badge sigap-badge--warning">

                                Pending

                            </span>
                        @endif

                    </div>

                </div>

            </div>

            <hr class="my-4">

            <h5 class="mb-4">

                Monitoring yang Dilakukan

            </h5>

            @if ($cppt->actionPhotos->count())

                <div class="row">

                    @foreach ($cppt->actionPhotos as $photo)
                        <div class="col-md-6 mb-4">

                            <div class="border rounded-4 p-3 h-100">

                                <div class="d-flex justify-content-between mb-3">

                                    <strong>

                                        {{ $photo->action_name }}

                                    </strong>

                                    <span class="sigap-badge sigap-badge--secondary">

                                        {{ $photo->category }}

                                    </span>

                                </div>

                                <img src="{{ asset('storage/' . $photo->photo) }}"
                                    class="img-fluid rounded-4 shadow-sm">

                            </div>

                        </div>
                    @endforeach

                </div>
            @else
                <p class="text-muted">

                    Tidak ada tindakan.

                </p>

            @endif

            <h5 class="mb-3">

                Catatan Monitoring

            </h5>

            <div class="form-control mb-4" style="min-height:90px">

                {{ $cppt->monitoring_note ?: '-' }}

            </div>

            <h5 class="mb-3">

                SOAP

            </h5>

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Subjective

                    </label>

                    <div class="form-control" style="min-height:120px">

                        {{ $cppt->subjective }}

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Objective

                    </label>

                    <div class="form-control" style="min-height:120px">

                        {{ $cppt->objective }}

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Assessment

                    </label>

                    <div class="form-control" style="min-height:120px">

                        {{ $cppt->assessment }}

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label fw-semibold">

                        Plan

                    </label>

                    <div class="form-control" style="min-height:120px">

                        {{ $cppt->plan }}

                    </div>

                </div>

            </div>

            {{-- @if ($cppt->photo)
                <hr class="my-4">

                <h5 class="mb-3">

                    Foto Dokumentasi

                </h5>

                <img src="{{ asset('storage/' . $cppt->photo) }}" class="img-fluid rounded-4 shadow-sm border"
                    style="max-width:500px">
            @endif --}}

            @if ($cppt->doctor_note)
                <hr class="my-4">

                <h5>

                    Feedback Dokter

                </h5>

                <div class="alert alert-info rounded-4">

                    <i class="fa-solid fa-user-doctor me-2"></i>

                    {{ $cppt->doctor_note }}

                </div>
            @endif

        </section>

    </div>

</x-layouts.app>

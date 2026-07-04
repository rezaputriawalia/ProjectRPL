<x-layouts.app
    title="Detail CPPT"
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
                <h1>Detail CPPT</h1>
                <p>Detail hasil Catatan Perkembangan Pasien Terintegrasi.</p>
            </div>

            <a href="{{ route('doctor.cppts.index') }}"
                class="sigap-button sigap-button--secondary sigap-button--md">

                <i class="fa-solid fa-arrow-left"></i>

                Kembali

            </a>

        </div>

        <section class="admin-panel">

            {{-- INFORMASI PASIEN + MONITORING --}}
            <div class="row g-4">

                <div class="col-lg-5">

                    <div class="sigap-table-card p-4">

                        <h5 class="mb-4">Informasi Pasien</h5>

                        <table class="table table-borderless align-middle mb-0">

                            <tr>
                                <th width="140">Nama</th>
                                <td><strong>{{ $cppt->registration->patient->name }}</strong></td>
                            </tr>

                            <tr>
                                <th>No. RM</th>
                                <td>{{ $cppt->registration->patient->medical_record_number }}</td>
                            </tr>

                            <tr>
                                <th>Ruangan</th>
                                <td>{{ $cppt->registration->room->name }}</td>
                            </tr>

                            <tr>
                                <th>Perawat</th>
                                <td>{{ $cppt->nurse?->name ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($cppt->verification_status == 'verified')
                                        <span class="sigap-badge sigap-badge--success">
                                            Verified
                                        </span>
                                    @else
                                        <span class="sigap-badge sigap-badge--warning">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        </table>

                    </div>

                </div>

                <div class="col-lg-7">

                    <div class="sigap-table-card p-4">

                        <h5 class="mb-4">Monitoring yang Dilakukan</h5>

                        @if ($cppt->actionPhotos->count())

                            <div class="row">

                                @foreach ($cppt->actionPhotos as $photo)

                                    <div class="col-md-6 mb-4">

                                        <div class="border rounded-4 p-3 h-100">

                                            <div class="d-flex justify-content-between mb-3">

                                                <strong>{{ $photo->action_name }}</strong>

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
                                Tidak ada tindakan monitoring.
                            </p>

                        @endif

                    </div>

                </div>

            </div>

            {{-- SOAP --}}
            <div class="sigap-table-card p-4 mt-4">

                <h5 class="mb-4">SOAP</h5>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="fw-semibold mb-2">Subjective</label>

                        <div class="p-3 rounded bg-light" style="min-height:120px">
                            {{ $cppt->subjective ?: '-' }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <label class="fw-semibold mb-2">Objective</label>

                        <div class="p-3 rounded bg-light" style="min-height:120px">
                            {{ $cppt->objective ?: '-' }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <label class="fw-semibold mb-2">Assessment</label>

                        <div class="p-3 rounded bg-light" style="min-height:120px">
                            {{ $cppt->assessment ?: '-' }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <label class="fw-semibold mb-2">Plan</label>

                        <div class="p-3 rounded bg-light" style="min-height:120px">
                            {{ $cppt->plan ?: '-' }}
                        </div>

                    </div>

                </div>

            </div>

            {{-- CATATAN --}}
            <div class="row g-4 mt-1">

                <div class="col-lg-12">

                    <div class="sigap-table-card p-4">

                        <h5 class="mb-3">Catatan Monitoring</h5>

                        <div class="p-3 rounded bg-light" style="min-height:120px">
                            {{ $cppt->monitoring_note ?: '-' }}
                        </div>

                    </div>

                </div>

            </div>

            {{-- VERIFIKASI --}}
            @if ($cppt->verification_status == 'pending')

                <form method="POST"
                    action="{{ route('doctor.cppts.update', $cppt) }}">

                    @csrf
                    @method('PUT')

                    <div class="sigap-table-card p-4 mt-4">

                        <h5 class="mb-3">

                            Feedback Dokter

                        </h5>

                        <label class="form-label fw-semibold">

                            Catatan untuk Perawat

                        </label>

                        <textarea
                            name="doctor_note"
                            class="form-control"
                            rows="5"
                            placeholder="Tuliskan arahan atau feedback untuk perawat..."
                            required>{{ old('doctor_note') }}</textarea>

                    </div>

                    <div class="d-flex justify-content-end mt-4">

                        <button
                            type="submit"
                            class="sigap-button sigap-button--primary sigap-button--lg">

                            <i class="fa-solid fa-circle-check"></i>

                            Verifikasi CPPT

                        </button>

                    </div>

                </form>

            @else

                <div class="alert alert-success rounded-4 border-0 shadow-sm mt-4">

                    <i class="fa-solid fa-circle-check me-2"></i>

                    CPPT telah diverifikasi oleh

                    <strong>{{ $cppt->verifier?->name }}</strong>

                    <br>

                    <small>{{ $cppt->verified_at }}</small>

                    @if ($cppt->doctor_note)

                        <hr>

                        <h6 class="fw-bold mb-2">

                            Feedback Dokter

                        </h6>

                        <p class="mb-0">

                            {{ $cppt->doctor_note }}

                        </p>

                    @endif

                </div>

            @endif

        </section>

    </div>

</x-layouts.app>
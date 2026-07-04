<x-layouts.app title="Input CPPT" role="perawat" brand="SIGAP Perawat" subtitle="Rumah Sakit Jiwa" active="patients"
    :nav-items="$navItems" :userName="auth()->user()->name" userRole="Perawat">

    <div class="admin-dashboard">

        <div class="admin-dashboard__header d-flex justify-content-between align-items-center">

            <div>

                <h1>Input CPPT</h1>

                <p>

                    Pasien
                    <strong>{{ $patient->name }}</strong>

                </p>

            </div>

            <a href="{{ route('perawat.patients.cppts.index', $patient) }}"
                class="sigap-button sigap-button--secondary sigap-button--md">

                <i class="fa-solid fa-arrow-left"></i>

                Kembali

            </a>

        </div>

        <section class="admin-panel">

            @if ($errors->any())

                <div class="alert alert-danger rounded-4 mb-4">

                    <strong>Terjadi Kesalahan</strong>

                    <ul class="mb-0 mt-2">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif

            <form method="POST" enctype="multipart/form-data"
                action="{{ route('perawat.patients.cppts.store', $patient) }}">

                @csrf

                <h4 class="mb-4">

                    Monitoring Hari Ini

                </h4>

                <div class="row g-4">

                    <div class="col-lg-6">

                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body">

                                <h5 class="mb-3">

                                    Terapi Aktivitas Kelompok (TAK)

                                </h5>

                                @forelse($monitoring->items->where('category','TAK') as $item)
                                    <div class="border rounded-4 p-3 mb-3">

                                        <div class="form-check">

                                            <input class="form-check-input action-checkbox" type="checkbox"
                                                id="item{{ $item->id }}" name="selected_actions[]"
                                                value="{{ $item->action }}">

                                            <label class="form-check-label fw-semibold" for="item{{ $item->id }}">

                                                {{ $item->action }}

                                            </label>

                                        </div>

                                        <div class="mt-3">

                                            <label class="form-label small text-muted">

                                                Bukti Foto

                                            </label>

                                            <input type="file" class="form-control action-photo"
                                                name="photos[{{ $item->action }}]" disabled>

                                        </div>

                                    </div>

                                @empty

                                    <p class="text-muted">

                                        Tidak ada tindakan TAK.

                                    </p>
                                @endforelse

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-6">

                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body">

                                <h5 class="mb-3">

                                    Activity Daily Living (ADL)

                                </h5>

                                @forelse($monitoring->items->where('category','ADL') as $item)
                                    <div class="border rounded-4 p-3 mb-3">

                                        <div class="form-check">

                                            <input class="form-check-input action-checkbox" type="checkbox"
                                                id="item{{ $item->id }}" name="selected_actions[]"
                                                value="{{ $item->action }}">

                                            <label class="form-check-label fw-semibold" for="item{{ $item->id }}">

                                                {{ $item->action }}

                                            </label>

                                        </div>

                                        <div class="mt-3">

                                            <label class="form-label small text-muted">

                                                Bukti Foto

                                            </label>

                                            <input type="file" class="form-control action-photo"
                                                name="photos[{{ $item->action }}]" disabled>

                                        </div>

                                    </div>

                                @empty

                                    <p class="text-muted">

                                        Tidak ada tindakan ADL.

                                    </p>
                                @endforelse

                            </div>

                        </div>

                    </div>

                </div>

                <hr class="my-5">

                {{-- <div class="mb-4">

                    <label class="form-label fw-semibold">

                        Foto Dokumentasi

                    </label>

                    <input type="file" name="photo" class="form-control">

                </div> --}}

                <div class="mb-4">

                    <label class="form-label fw-semibold">

                        Catatan Monitoring

                    </label>

                    <textarea name="monitoring_note" rows="4" class="form-control">{{ old('monitoring_note') }}</textarea>

                </div>

                <h4 class="mb-4">

                    SOAP

                </h4>

                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-semibold">

                            Subjective

                        </label>

                        <textarea name="subjective" rows="5" class="form-control" required>{{ old('subjective') }}</textarea>

                    </div>

                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-semibold">

                            Objective

                        </label>

                        <textarea name="objective" rows="5" class="form-control" required>{{ old('objective') }}</textarea>

                    </div>

                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-semibold">

                            Assessment

                        </label>

                        <textarea name="assessment" rows="5" class="form-control" required>{{ old('assessment') }}</textarea>

                    </div>

                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-semibold">

                            Plan

                        </label>

                        <textarea name="plan" rows="5" class="form-control" required>{{ old('plan') }}</textarea>

                    </div>

                </div>

                <div class="d-flex justify-content-end mt-4">

                    <button type="submit" class="sigap-button sigap-button--primary sigap-button--lg">

                        <i class="fa-solid fa-floppy-disk"></i>

                        Simpan CPPT

                    </button>

                </div>

            </form>

        </section>

    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.action-checkbox').forEach(function(checkbox) {

                checkbox.addEventListener('change', function() {

                    let fileInput = this.closest('.border')
                        .querySelector('.action-photo');

                    fileInput.disabled = !this.checked;

                    if (!this.checked) {

                        fileInput.value = '';

                    }

                });

            });
        </script>
    @endpush

</x-layouts.app>

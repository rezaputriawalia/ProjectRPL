<x-layouts.app
    title="Registrasi Pasien"
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

            <h1>Registrasi Pasien</h1>

            <p>
                Tambahkan pasien baru ke bangsal Anda.
            </p>

        </div>

        <a href="{{ route('perawat.patients.index') }}"
           class="sigap-button sigap-button--secondary sigap-button--md">

            <i class="fa-solid fa-arrow-left"></i>

            Kembali

        </a>

    </div>

    <section class="admin-panel">

        <form method="POST" action="{{ route('perawat.patients.store') }}">

            @csrf

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="sigap-label">

                        Nama Pasien

                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control sigap-form-control"
                        required>

                </div>

                <div class="col-md-6">

                    <label class="sigap-label">

                        NIK

                    </label>

                    <input
                        type="text"
                        name="nik"
                        value="{{ old('nik') }}"
                        class="form-control sigap-form-control"
                        required>

                </div>

                <div class="col-md-6">

                    <label class="sigap-label">

                        Jenis Kelamin

                    </label>

                    <select
                        name="gender"
                        class="form-select sigap-form-control">

                        <option value="L" {{ old('gender')=='L' ? 'selected' : '' }}>
                            Laki-laki
                        </option>

                        <option value="P" {{ old('gender')=='P' ? 'selected' : '' }}>
                            Perempuan
                        </option>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="sigap-label">

                        Tanggal Lahir

                    </label>

                    <input
                        type="date"
                        name="birth_date"
                        value="{{ old('birth_date') }}"
                        class="form-control sigap-form-control"
                        required>

                </div>

                <div class="col-md-6">

                    <label class="sigap-label">

                        Nomor HP

                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="form-control sigap-form-control">

                </div>

                <div class="col-md-6">

                    <label class="sigap-label">

                        Dokter Penanggung Jawab

                    </label>

                    <select
                        name="doctor_id"
                        class="form-select sigap-form-control"
                        required>

                        <option value="">

                            -- Pilih Dokter --

                        </option>

                        @foreach($doctors as $doctor)

                        <option
                            value="{{ $doctor->id }}"
                            {{ old('doctor_id')==$doctor->id ? 'selected':'' }}>

                            {{ $doctor->name }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="sigap-label">

                        Ruangan

                    </label>

                    <select
                        name="room_id"
                        class="form-select sigap-form-control"
                        required>

                        <option value="">

                            -- Pilih Ruangan --

                        </option>

                        @foreach($rooms as $room)

                        <option
                            value="{{ $room->id }}"
                            {{ old('room_id')==$room->id ? 'selected':'' }}>

                            {{ $room->name }}
                            ({{ \App\Models\Registration::where('room_id',$room->id)->where('status','active')->count() }}/{{ $room->capacity }} TT)

                        </option>

                        @endforeach

                    </select>

                    @error('room_id')

                        <small class="text-danger">

                            {{ $message }}

                        </small>

                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="sigap-label">

                        Status Perawatan

                    </label>

                    <select
                        name="status"
                        class="form-select sigap-form-control">

                        <option value="rawat_inap"
                            {{ old('status')=='rawat_inap' ? 'selected':'' }}>

                            Rawat Inap

                        </option>

                        <option value="rawat_jalan"
                            {{ old('status')=='rawat_jalan' ? 'selected':'' }}>

                            Rawat Jalan

                        </option>

                    </select>

                </div>

                <div class="col-12">

                    <label class="sigap-label">

                        Alamat

                    </label>

                    <textarea
                        rows="4"
                        name="address"
                        class="form-control sigap-form-control"
                        required>{{ old('address') }}</textarea>

                </div>

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

                    Registrasi Pasien

                </button>

            </div>

        </form>

    </section>

</div>

</x-layouts.app>
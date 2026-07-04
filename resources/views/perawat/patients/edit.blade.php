<x-layouts.app
    title="Edit Pasien"
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

            <h1>Edit Pasien</h1>

            <p>

                Perbarui data pasien pada bangsal Anda.

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

        <form method="POST" action="{{ route('perawat.patients.update',$patient) }}">

            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Nama Pasien

                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name',$patient->name) }}"
                        required>

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        NIK

                    </label>

                    <input
                        type="text"
                        name="nik"
                        class="form-control"
                        value="{{ old('nik',$patient->nik) }}"
                        required>

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Jenis Kelamin

                    </label>

                    <select
                        name="gender"
                        class="form-select">

                        <option value="L"
                            {{ old('gender',$patient->gender)=='L' ? 'selected':'' }}>

                            Laki-laki

                        </option>

                        <option value="P"
                            {{ old('gender',$patient->gender)=='P' ? 'selected':'' }}>

                            Perempuan

                        </option>

                    </select>

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Tanggal Lahir

                    </label>

                    <input
                        type="date"
                        name="birth_date"
                        class="form-control"
                        value="{{ old('birth_date',$patient->birth_date->format('Y-m-d')) }}"
                        required>

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Nomor HP

                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="{{ old('phone',$patient->phone) }}">

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Dokter Penanggung Jawab

                    </label>

                    <select
                        name="doctor_id"
                        class="form-select"
                        required>

                        @foreach($doctors as $doctor)

                            <option
                                value="{{ $doctor->id }}"
                                {{ old('doctor_id',$registration->doctor_id)==$doctor->id ? 'selected':'' }}>

                                {{ $doctor->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label fw-semibold">

                        Ruangan

                    </label>

                    <select
                        name="room_id"
                        class="form-select"
                        required>

                        @foreach($rooms as $room)

                            <option
                                value="{{ $room->id }}"
                                {{ old('room_id',$registration->room_id)==$room->id ? 'selected':'' }}>

                                {{ $room->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-12 mb-4">

                    <label class="form-label fw-semibold">

                        Alamat

                    </label>

                    <textarea
                        name="address"
                        rows="4"
                        class="form-control"
                        required>{{ old('address',$patient->address) }}</textarea>

                </div>

            </div>

            <div class="d-flex justify-content-end gap-3">

                <a
                    href="{{ route('perawat.patients.index') }}"
                    class="sigap-button sigap-button--secondary sigap-button--md">

                    Batal

                </a>

                <button
                    type="submit"
                    class="sigap-button sigap-button--primary sigap-button--md">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </section>

</div>

</x-layouts.app>
<x-layouts.app
    title="Tambah Ruangan"
    role="admin"
    brand="SIGAP Admin"
    subtitle="Rumah Sakit Jiwa"
    active="rooms"
    :nav-items="$navItems"
    :userName="auth()->user()->name"
    userRole="Administrator"
>

<div class="admin-dashboard">

    <div class="admin-dashboard__header d-flex justify-content-between align-items-center">

        <div>

            <h1>Tambah Ruangan</h1>

            <p>
                Tambahkan data ruangan baru pada bangsal.
            </p>

        </div>

        <a
            href="{{ route('admin.rooms.index') }}"
            class="sigap-button sigap-button--secondary sigap-button--md">

            <i class="fa-solid fa-arrow-left"></i>

            Kembali

        </a>

    </div>

    <section class="admin-panel">

        <form
            method="POST"
            action="{{ route('admin.rooms.store') }}">

            @csrf

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">

                        Bangsal

                    </label>

                    <select
                        name="ward_id"
                        class="form-select @error('ward_id') is-invalid @enderror"
                        required>

                        <option value="">

                            -- Pilih Bangsal --

                        </option>

                        @foreach($wards as $ward)

                            <option
                                value="{{ $ward->id }}"
                                {{ old('ward_id')==$ward->id ? 'selected' : '' }}>

                                {{ $ward->name }}

                            </option>

                        @endforeach

                    </select>

                    @error('ward_id')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Nama Ruangan

                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        required>

                    @error('name')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Kapasitas (Tempat Tidur)

                    </label>

                    <input
                        type="number"
                        name="capacity"
                        min="1"
                        value="{{ old('capacity') }}"
                        class="form-control @error('capacity') is-invalid @enderror"
                        required>

                    @error('capacity')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Status

                    </label>

                    <select
                        name="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required>

                        <option
                            value="available"
                            {{ old('status')=='available' ? 'selected' : '' }}>

                            Tersedia

                        </option>

                        <option
                            value="full"
                            {{ old('status')=='full' ? 'selected' : '' }}>

                            Penuh

                        </option>

                        <option
                            value="maintenance"
                            {{ old('status')=='maintenance' ? 'selected' : '' }}>

                            Maintenance

                        </option>

                    </select>

                    @error('status')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

            </div>

            <div class="d-flex justify-content-end gap-3 mt-5">

                <a
                    href="{{ route('admin.rooms.index') }}"
                    class="sigap-button sigap-button--secondary sigap-button--md">

                    Batal

                </a>

                <button
                    type="submit"
                    class="sigap-button sigap-button--primary sigap-button--md">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Simpan Ruangan

                </button>

            </div>

        </form>

    </section>

</div>

</x-layouts.app>
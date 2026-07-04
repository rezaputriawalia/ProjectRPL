<x-layouts.app
    title="Edit Bangsal"
    role="admin"
    brand="SIGAP Admin"
    subtitle="Rumah Sakit Jiwa"
    active="wards"
    :nav-items="$navItems"
    :userName="auth()->user()->name"
    userRole="Administrator"
>

<div class="admin-dashboard">

    <div class="admin-dashboard__header d-flex justify-content-between align-items-center">

        <div>

            <h1>Edit Bangsal</h1>

            <p>
                Perbarui informasi bangsal Rumah Sakit Jiwa.
            </p>

        </div>

        <a
            href="{{ route('admin.wards.index') }}"
            class="sigap-button sigap-button--secondary sigap-button--md">

            <i class="fa-solid fa-arrow-left"></i>

            Kembali

        </a>

    </div>

    <section class="admin-panel">

        <form
            method="POST"
            action="{{ route('admin.wards.update',$ward) }}">

            @csrf
            @method('PUT')

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">

                        Nama Bangsal

                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name',$ward->name) }}"
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

                        Kapasitas

                    </label>

                    <input
                        type="number"
                        name="capacity"
                        value="{{ old('capacity',$ward->capacity) }}"
                        min="0"
                        class="form-control @error('capacity') is-invalid @enderror"
                        required>

                    @error('capacity')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                <div class="col-12">

                    <label class="form-label">

                        Deskripsi

                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="form-control">{{ old('description',$ward->description) }}</textarea>

                </div>

            </div>

            <div class="d-flex justify-content-end gap-3 mt-5">

                <a
                    href="{{ route('admin.wards.index') }}"
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
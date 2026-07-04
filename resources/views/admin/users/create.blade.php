<x-layouts.app
    title="Tambah User"
    role="admin"
    brand="SIGAP Admin"
    subtitle="Rumah Sakit Jiwa"
    active="users"
    :nav-items="$navItems"
    :userName="auth()->user()->name"
    userRole="Administrator"
>

<div class="admin-dashboard">

    <div class="admin-dashboard__header d-flex justify-content-between align-items-center">

        <div>

            <h1>Tambah User</h1>

            <p>
                Tambahkan akun Administrator, Dokter, atau Perawat.
            </p>

        </div>

        <a
            href="{{ route('admin.users.index') }}"
            class="sigap-button sigap-button--secondary sigap-button--md">

            Kembali

        </a>

    </div>

    <section class="admin-panel">

        <form
            method="POST"
            action="{{ route('admin.users.store') }}"
            autocomplete="off">

            @csrf

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">

                        Nama

                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        autocomplete="off"
                        required>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Email

                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        autocomplete="new-email"
                        required>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Nomor HP

                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        autocomplete="off">

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Role

                    </label>

                    <select
                        id="role_id"
                        name="role_id"
                        class="form-select"
                        required>

                        @foreach($roles as $role)

                        <option
                            value="{{ $role->id }}"
                            data-role="{{ $role->name }}">

                            {{ $role->display_name }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div
                    class="col-md-6"
                    id="wardField"
                    style="display:none;">

                    <label class="form-label">

                        Bangsal

                    </label>

                    <select
                        name="ward_id"
                        class="form-select"
                        autocomplete="off">

                        <option value="">

                            -- Pilih Bangsal --

                        </option>

                        @foreach($wards as $ward)

                        <option value="{{ $ward->id }}">

                            {{ $ward->name }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Password

                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        autocomplete="new-password"
                        required>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Status

                    </label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="active">

                            Active

                        </option>

                        <option value="inactive">

                            Inactive

                        </option>

                    </select>

                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-5">

                <a
                    href="{{ route('admin.users.index') }}"
                    class="sigap-button sigap-button--secondary sigap-button--md">

                    Batal

                </a>

                <button
                    type="submit"
                    class="sigap-button sigap-button--primary sigap-button--md">

                    Simpan User

                </button>

            </div>

        </form>

    </section>

</div>

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded',function(){

const role=document.getElementById('role_id');

const wardField=document.getElementById('wardField');

function toggleWard(){

const selected=role.options[role.selectedIndex];

const wardSelect=wardField.querySelector('select');

if(selected.dataset.role==='nurse'){

wardField.style.display='block';

}else{

wardField.style.display='none';

wardSelect.value='';

}

}

toggleWard();

role.addEventListener('change',toggleWard);

});

</script>

@endpush

</x-layouts.app>
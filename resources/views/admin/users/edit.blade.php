<x-layouts.app
    title="Edit User"
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

            <h1>Edit User</h1>

            <p>
                Perbarui data akun Administrator, Dokter, maupun Perawat.
            </p>

        </div>

        <a
            href="{{ route('admin.users.index') }}"
            class="sigap-button sigap-button--secondary sigap-button--md">

            <i class="fa-solid fa-arrow-left"></i>

            Kembali

        </a>

    </div>

    <section class="admin-panel">

        <form
            method="POST"
            action="{{ route('admin.users.update',$user) }}">

            @csrf
            @method('PUT')

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="sigap-field">

                        <label class="sigap-field__label">

                            Nama

                        </label>

                        <input
                            type="text"
                            name="name"
                            class="sigap-input"
                            value="{{ old('name',$user->name) }}"
                            required>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="sigap-field">

                        <label class="sigap-field__label">

                            Email

                        </label>

                        <input
                            type="email"
                            name="email"
                            class="sigap-input"
                            value="{{ old('email',$user->email) }}"
                            required>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="sigap-field">

                        <label class="sigap-field__label">

                            No HP

                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="sigap-input"
                            value="{{ old('phone',$user->phone) }}">

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="sigap-field">

                        <label class="sigap-field__label">

                            Role

                        </label>

                        <select
                            id="role_id"
                            name="role_id"
                            class="sigap-select">

                            @foreach($roles as $role)

                                <option
                                    value="{{ $role->id }}"
                                    data-role="{{ $role->name }}"
                                    {{ old('role_id',$user->role_id)==$role->id?'selected':'' }}>

                                    {{ $role->display_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div
                    class="col-md-6"
                    id="wardField"
                    style="{{ $user->role->name=='nurse'?'':'display:none' }}">

                    <div class="sigap-field">

                        <label class="sigap-field__label">

                            Bangsal

                        </label>

                        <select
                            name="ward_id"
                            class="sigap-select">

                            <option value="">
                                -- Pilih Bangsal --
                            </option>

                            @foreach($wards as $ward)

                                <option
                                    value="{{ $ward->id }}"
                                    {{ old('ward_id',$user->ward_id)==$ward->id?'selected':'' }}>

                                    {{ $ward->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="sigap-field">

                        <label class="sigap-field__label">

                            Password Baru

                        </label>

                        <input
                            type="password"
                            name="password"
                            class="sigap-input"
                            placeholder="Kosongkan jika tidak ingin mengganti password">

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="sigap-field">

                        <label class="sigap-field__label">

                            Status

                        </label>

                        <select
                            name="status"
                            class="sigap-select">

                            <option
                                value="active"
                                {{ $user->status=='active'?'selected':'' }}>

                                Active

                            </option>

                            <option
                                value="inactive"
                                {{ $user->status=='inactive'?'selected':'' }}>

                                Inactive

                            </option>

                        </select>

                    </div>

                </div>

            </div>

            <div class="mt-5 d-flex gap-3">

                <button
                    class="sigap-button sigap-button--primary sigap-button--md">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Simpan Perubahan

                </button>

                <a
                    href="{{ route('admin.users.index') }}"
                    class="sigap-button sigap-button--secondary sigap-button--md">

                    Batal

                </a>

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
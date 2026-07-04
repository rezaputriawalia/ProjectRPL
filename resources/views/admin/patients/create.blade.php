<x-layouts.app
    title="Tambah User"
    role="admin"
    brand="SIGAP Admin"
    subtitle="Rumah Sakit Jiwa"

    active="users"

    :nav-items="$navItems"

    :userName="auth()->user()->name"
    userRole="Administrator">

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Tambah User</h2>
            <p class="text-muted">Tambahkan akun Admin, Dokter, atau Perawat.</p>
        </div>

        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Nama</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>No HP</label>
                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Role</label>

                        <select
                            name="role_id"
                            class="form-control"
                            required
                        >

                            @foreach($roles as $role)

                                <option value="{{ $role->id }}">
                                    {{ $role->display_name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Password</label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Status</label>

                        <select
                            name="status"
                            class="form-control"
                        >

                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>

                        </select>

                    </div>

                </div>

                <button class="btn btn-success">
                    Simpan
                </button>

            </form>

        </div>
    </div>

</div>

</x-layouts.app>
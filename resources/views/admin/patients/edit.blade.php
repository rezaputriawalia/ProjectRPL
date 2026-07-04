<x-layouts.app
    title="Edit User"
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
                <h2>Edit User</h2>
                <p class="text-muted">Tambahkan akun Admin, Dokter, atau Perawat.</p>
            </div>

            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST" action="{{ route('admin.users.update',$user) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Nama</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name',$user->name) }}"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email',$user->email) }}"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>No HP</label>
                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="{{ old('phone',$user->phone) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Role</label>

                            <select
                                name="role_id"
                                class="form-control"
                                required>

                                @foreach($roles as $role)

                                <option
                                    value="{{ $role->id }}"
                                    {{ old('role_id',$user->role_id)==$role->id ? 'selected':'' }}>
                                    {{ $role->display_name }}
                                </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Password Baru</label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Kosongkan jika tidak ingin mengganti password">
                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Status</label>

                            <select
                                name="status"
                                class="form-control">

                                <option
                                    value="active"
                                    {{ $user->status=='active' ? 'selected':'' }}>
                                    Active
                                </option>

                                <option
                                    value="inactive"
                                    {{ $user->status=='inactive' ? 'selected':'' }}>
                                    Inactive
                                </option>

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
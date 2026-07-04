<x-layouts.app
    title="Manajemen User"
    role="admin"
    brand="SIGAP Admin"
    subtitle="Rumah Sakit Jiwa"
    active="users"
    :nav-items="$navItems"
    :userName="auth()->user()->name"
    userRole="Administrator">

<div class="admin-dashboard">

    <div class="admin-dashboard__header d-flex justify-content-between align-items-center">

        <div>

            <h1>Manajemen User</h1>

            <p>
                Kelola akun Administrator, Dokter, dan Perawat.
            </p>

        </div>

        <a href="{{ route('admin.users.create') }}"
           class="sigap-button sigap-button--primary sigap-button--md">

            <i class="fa-solid fa-plus"></i>

            Tambah User

        </a>

    </div>

    <section class="admin-panel">

        <div class="sigap-table-card">

            <div class="sigap-table-card__scroll">

                <table class="sigap-table">

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Nama</th>

                            <th>Email</th>

                            <th>Role</th>

                            <th>Status</th>

                            <th width="170">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($users as $user)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>

                                <strong>{{ $user->name }}</strong>

                            </td>

                            <td>{{ $user->email }}</td>

                            <td>{{ $user->role->display_name }}</td>

                            <td>

                                @if($user->status=='active')

                                    <span class="sigap-badge sigap-badge--success">

                                        Aktif

                                    </span>

                                @else

                                    <span class="sigap-badge sigap-badge--danger">

                                        Nonaktif

                                    </span>

                                @endif

                            </td>

                            <td>

                                <div class="d-flex gap-2">

                                    <a href="{{ route('admin.users.edit',$user) }}"
                                       class="sigap-button sigap-button--secondary sigap-button--sm">

                                        Edit

                                    </a>

                                    <form action="{{ route('admin.users.destroy',$user) }}"
                                          method="POST"
                                          class="delete-form">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="sigap-button sigap-button--brown sigap-button--sm">

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-5">

                                Tidak ada data user.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    @if(session('success'))

    Swal.fire({

        icon: 'success',

        title: 'Berhasil',

        text: "{{ session('success') }}",

        timer: 1800,

        showConfirmButton: false

    });

    @endif

    @if(session('error'))

    Swal.fire({

        icon: 'error',

        title: 'Tidak Dapat Menghapus',

        text: "{{ session('error') }}",

        confirmButtonColor: '#4A835F'

    });

    @endif

    document.querySelectorAll('.delete-form').forEach(function(form){

        form.addEventListener('submit', function(e){

            e.preventDefault();

            Swal.fire({

                title: 'Hapus User?',

                text: 'Data yang dihapus tidak dapat dikembalikan.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#4A835F',

                cancelButtonColor: '#C7352E',

                confirmButtonText: 'Ya, Hapus',

                cancelButtonText: 'Batal'

            }).then((result)=>{

                if(result.isConfirmed){

                    form.submit();

                }

            });

        });

    });

});

</script>

</x-layouts.app>
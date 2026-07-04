<x-layouts.app
    title="Kelola Bangsal"
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

            <h1>Kelola Bangsal</h1>

            <p>
                Kelola seluruh data bangsal Rumah Sakit Jiwa.
            </p>

        </div>

        <a
            href="{{ route('admin.wards.create') }}"
            class="sigap-button sigap-button--primary sigap-button--md">

            <i class="fa-solid fa-plus"></i>

            Tambah Bangsal

        </a>

    </div>

    <section class="admin-panel">

        <div class="sigap-table-card">

            <div class="sigap-table-card__scroll">

                <table class="sigap-table">

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Nama Bangsal</th>

                            <th>Kapasitas</th>

                            <th>Deskripsi</th>

                            <th width="170">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($wards as $ward)

                        <tr>

                            <td>

                                {{ $loop->iteration }}

                            </td>

                            <td>

                                <strong>{{ $ward->name }}</strong>

                            </td>

                            <td>

                                {{ $ward->capacity }}

                            </td>

                            <td>

                                {{ $ward->description }}

                            </td>

                            <td>

                                <div class="d-flex gap-2">

                                    <a
                                        href="{{ route('admin.wards.edit',$ward) }}"
                                        class="sigap-button sigap-button--secondary sigap-button--sm">

                                        Edit

                                    </a>

                                    <form
                                        action="{{ route('admin.wards.destroy',$ward) }}"
                                        method="POST"
                                        class="delete-form">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="sigap-button sigap-button--brown sigap-button--sm">

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" style="text-align:center;padding:40px">

                                Belum ada data bangsal.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>

</div>

@if(session('success'))

<script>

document.addEventListener('DOMContentLoaded',function(){

Swal.fire({

icon:'success',

title:'Berhasil',

text:"{{ session('success') }}",

timer:1800,

showConfirmButton:false

});

});

</script>

@endif

<script>

document.querySelectorAll('.delete-form').forEach(form=>{

form.addEventListener('submit',function(e){

e.preventDefault();

Swal.fire({

title:'Hapus Bangsal?',

text:'Data yang dihapus tidak dapat dikembalikan.',

icon:'warning',

showCancelButton:true,

confirmButtonColor:'#4A835F',

cancelButtonColor:'#C7352E',

confirmButtonText:'Ya, Hapus',

cancelButtonText:'Batal'

}).then(result=>{

if(result.isConfirmed){

form.submit();

}

});

});

});

</script>

</x-layouts.app>
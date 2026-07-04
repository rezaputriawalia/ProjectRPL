<x-layouts.app
    title="Kelola Ruangan"
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

            <h1>Kelola Ruangan</h1>

            <p>
                Kelola seluruh data ruangan pada setiap bangsal.
            </p>

        </div>

        <a
            href="{{ route('admin.rooms.create') }}"
            class="sigap-button sigap-button--primary sigap-button--md">

            <i class="fa-solid fa-plus"></i>

            Tambah Ruangan

        </a>

    </div>

    <section class="admin-panel">

        <div class="sigap-table-card">

            <div class="sigap-table-card__scroll">

                <table class="sigap-table">

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Bangsal</th>

                            <th>Nama Ruangan</th>

                            <th>Kapasitas</th>

                            <th>Status</th>

                            <th width="190">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($rooms as $room)

                        <tr>

                            <td>

                                {{ $loop->iteration }}

                            </td>

                            <td>

                                <strong>{{ $room->ward->name }}</strong>

                            </td>

                            <td>

                                {{ $room->name }}

                            </td>

                            <td>

                                <span class="sigap-badge sigap-badge--info">

                                    {{ $room->capacity }} TT

                                </span>

                            </td>

                            <td>

                                @if($room->status=='available')

                                    <span class="sigap-badge sigap-badge--success">

                                        <i class="fa-solid fa-circle-check"></i>

                                        Tersedia

                                    </span>

                                @elseif($room->status=='full')

                                    <span class="sigap-badge sigap-badge--danger">

                                        <i class="fa-solid fa-ban"></i>

                                        Penuh

                                    </span>

                                @else

                                    <span class="sigap-badge sigap-badge--warning">

                                        <i class="fa-solid fa-screwdriver-wrench"></i>

                                        Maintenance

                                    </span>

                                @endif

                            </td>

                            <td>

                                <div class="d-flex gap-2">

                                    <a
                                        href="{{ route('admin.rooms.edit',$room) }}"
                                        class="sigap-button sigap-button--secondary sigap-button--sm">

                                        Edit

                                    </a>

                                    <form
                                        action="{{ route('admin.rooms.destroy',$room) }}"
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

                            <td colspan="6" style="text-align:center;padding:40px">

                                Belum ada data ruangan.

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

title:'Hapus Ruangan?',

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
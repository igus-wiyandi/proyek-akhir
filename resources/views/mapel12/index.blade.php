@extends('admin.layout')
@section('content')

<div class="w-full p-6 bg-gray-100 min-h-screen">
    <div class="w-full max-w-7xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">

        @if (session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition
            class="flex justify-center mt-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-teal-600 p-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Kelas 12</h2>
            <a href="{{ route('mapel12.create') }}" class="text-white hover:text-teal-100 transition-colors" title="Tambah Data">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </a>
        </div>

        <div class="p-6">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-teal-50">
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">No</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Nama Pelajaran</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Tanggal</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Jam Mulai</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Jam Selesai</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Guru Mengajar</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Aksi</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Guru</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $no = ($mapel12->currentPage() - 1) * $mapel12->perPage() + 1; ?>
                       @foreach ($mapel12 as $m)
                         <tr class="hover:bg-gray-50">
                           <td class="p-3 border border-gray-200">{{ $no++ }}</td>
                           <td class="p-3 border border-gray-200">{{ $m->nama }}</td>
                           <td class="p-3 border border-gray-200">
                            {{ \Carbon\Carbon::parse($m->tanggal_dihitung ?? $m->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                            </td>
                           <td class="p-3 border border-gray-200">{{ $m->jam_mulai }}</td>
                           <td class="p-3 border border-gray-200">{{ $m->jam_selesai }}</td>
                           <td class="p-3 border border-gray-200">{{ $m->guru->nama ?? 'Belum dipilih' }}</td>
                           <td class="p-3 border border-gray-200">
                            <div class="flex space-x-3">
                                <a href="{{ route('mapel12.edit', $m->id) }}" class="text-teal-600 hover:text-teal-800 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('mapel12.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Yakin ingin dihapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0h4m-7 4h10"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                           </td>

                           <td class="p-3 border border-gray-200">
                            <div class="mt-1">
                                @if(is_null($m->guru_id))
                                    {{ $m->guru->nama ?? '' }}
                                @endif

                               <button onclick="openGuruModal({{ $m->id }}, '{{ $m->nama }}', {{ $m->guru_id ?? 'null' }})"
                                    class="mt-1 px-3 py-1.5 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-all duration-200 flex items-center space-x-1.5 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 018 0zM12 21v-2m0-2H9m3 2h3"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Pilih Guru</span>
                                </button>
                            </div>
                        </td>

                          </div>
                       </tr>
                       @endforeach

                    </tbody>
                </table>
            </div>

            <div class="pull-right my-4">
                {{ $mapel12->links() }}
            </div>

    </div>
</div>


<div id="guruModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white w-full max-w-md p-6 rounded shadow-lg relative">

        <h2 class="text-lg font-bold text-teal-700 mb-4">Pilih Guru untuk Mapel</h2>

        <form id="guruForm12" method="POST" action="">
            @csrf
            <input type="hidden" name="_method" value="POST">

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Nama Mapel:</label>
                <input type="text" id="mapelNama" class="w-full p-2 border bg-gray-100 rounded" readonly>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Pilih Guru:</label>
                <select name="guru_id" id="guruSelect" class="w-full border rounded p-2">
                    <option value=""> Kosongkan Guru </option>
                    @foreach($guru12 as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }}</option>
                    @endforeach
                </select>
            </div>


            <div class="flex justify-end space-x-2">
                <button type="submit" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600">Simpan</button>
                <button type="button" onclick="closeGuruModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openGuruModal(mapelId, mapelNama, guruIdTerpilih = null) {

        const form = document.getElementById('guruForm12');
        form.action = `/mapel12/${mapelId}/pilih-guru12`;


        document.getElementById('mapelNama').value = mapelNama;


        const select = document.getElementById('guruSelect');
        [...select.options].forEach(opt => {
    opt.selected = guruIdTerpilih === null ? opt.value === "" : parseInt(opt.value) === guruIdTerpilih;
    });


        document.getElementById('guruModal').classList.remove('hidden');
        document.getElementById('guruModal').classList.add('flex');
    }

    function closeGuruModal() {
        const modal = document.getElementById('guruModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>


@endsection

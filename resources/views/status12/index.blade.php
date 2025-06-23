@extends('tampil_guru.layout')
@section('content')
<div class="w-full p-6 bg-gray-100 ">

    <div class="w-full bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-teal-300 p-4">
            <h2 class="text-xl font-bold text-teal-800">Kelas 12</h2>
        </div>

            <div class="mb-6">

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-teal-300">
                            <th class="p-3 text-left text-teal-800 border border-teal-400">No</th>
                            <th class="p-3 text-left text-teal-800 border border-teal-400">Nama Pelajaran</th>
                            <th class="p-3 text-left text-teal-800 border border-teal-400">Tanggal</th>
                            <th class="p-3 text-left text-teal-800 border border-teal-400">Jam Mulai</th>
                            <th class="p-3 text-left text-teal-800 border border-teal-400">Jam Selesai</th>
                            <th class="p-3 text-left text-teal-800 border border-teal-400">Guru Mengajar</th>

                        </tr>
                    </thead>
                    <tbody>

                        <?php
                            $no = 1;
                        ?>
                     @forelse($mapel12 as $m)
                     <tr class="hover:bg-gray-50">
                         <td class="p-3 border border-gray-200">{{ $loop->iteration }}</td>
                         <td class="p-3 border border-gray-200">{{ $m->nama }}</td>
                         <td class="p-3 border border-gray-200">
                            {{ \Carbon\Carbon::parse($m->tanggal_dihitung ?? $m->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </td>
                         <td class="p-3 border border-gray-200">{{ $m->jam_mulai }}</td>
                         <td class="p-3 border border-gray-200">{{ $m->jam_selesai }}</td>
                         <td class="p-3 border border-gray-200">{{ $m->guru->nama ?? '-' }}</td>

                     </tr>
                     @empty
                     <tr>
                         <td colspan="7" class="text-center p-4 text-gray-500">Belum ada data mapel.</td>
                     </tr>
                     @endforelse

                    </tbody>
                </table>
                {{-- <div class="flex justify-between my-4">
                    <a href="{{ route('status12.index', ['minggu' => $mingguOffset - 1]) }}"
                       class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded">Minggu Lalu</a>

                    <a href="{{ route('status12.index', ['minggu' => $mingguOffset + 1]) }}"
                       class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded">Minggu Depan</a>
                </div> --}}
            </div>
    </div>
</div>
@endsection

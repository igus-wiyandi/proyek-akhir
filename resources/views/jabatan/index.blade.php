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
            <h2 class="text-xl font-semibold text-white">Jabatan</h2>
            <a href="{{ route('jabatan.create') }}" class="text-white hover:text-teal-100 transition-colors" title="Tambah Data">
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
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Nama </th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Jabatan </th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $no = ($jabatan->currentPage() - 1) * $jabatan->perPage() + 1; ?>

                        @foreach ($jabatan as $j)

                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border border-gray-200">
                                {{ $no++ }}
                            </td>

                            <td class="p-3 border border-gray-200">
                                {{ $j->guru->nama }}
                            </td>


                            <td class="p-3 border border-gray-200">
                                {{ $j->kategori->nama }}
                            </td>

                            <td class="p-3 border border-gray-200">
                            <div class="flex space-x-3">
                                <a href="{{ route('jabatan.edit', $j->id) }}" class="text-teal-600 hover:text-teal-800 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('jabatan.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Yakin ingin dihapus?')">
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
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="pull-right my-4">
                {{ $jabatan->links() }}
            </div>
    </div>
</div>


@endsection

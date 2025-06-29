@extends('admin.layout')
@section('content')
<div class="w-full p-6 bg-gray-100 ">
    <div class="w-full bg-white rounded-lg shadow-md overflow-hidden">

            <div class="p-6">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-teal-50">
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">No</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Nama</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Email</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">No HP</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Alamat</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $no = ($guru->currentPage() - 1) * $guru->perPage() + 1; ?>

                        @foreach ($guru as $gurus)

                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border border-gray-200">
                                {{ $no++ }}
                            </td>

                            <td class="p-3 border border-gray-200">
                                {{ $gurus->nama }}
                            </td>

                            <td class="p-3 border border-gray-200">
                                {{ $gurus->email }}
                            </td>

                            <td class="p-3 border border-gray-200">
                                {{ $gurus->no_hp }}
                            </td>

                            <td class="p-3 border border-gray-200">
                                {{ $gurus->alamat }}
                            </td>

                            <td class="p-3 border border-gray-200">
                                <div class="flex space-x-3">
                                    <form action="{{ route('guru.destroy', $gurus->id) }}" method="POST" onsubmit="return confirm('Yakin ingin dihapus?')">
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
                {{ $guru->links() }}
            </div>
    </div>
</div>


@endsection

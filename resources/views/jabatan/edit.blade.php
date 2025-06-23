@extends('admin.layout')
@section('content')

<div class="w-full p-6 bg-gray-100 min-h-screen">
    <div class="w-full  bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-teal-600 p-4">
            <h2 class="text-xl font-semibold text-white">Ubah Jabatan</h2>
        </div>

        <form method="POST" action="{{ route('jabatan.update',$jabatan->id) }}" class="p-6">
            @method('PUT')
            @csrf

            <div class="mb-3">
                <label for="guru_id" class="form-label"
                    class="block text-gray-700 font-medium mb-2">Nama </label>
                <select name="guru_id" id="guru_id"
                    class="form-control w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-300"
                    required>
                    <option value="" disabled {{ old('guru_id', $jabatan->guru_id) ? '' : 'selected' }}>-- Pilih Guru --</option>
                    @foreach ($guru as $g)
                        <<option value="{{ $g->id }}" {{ old('guru_id', $jabatan->guru_id) == $g->id ? 'selected' : '' }}>
                            {{ $g->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="kategori_id" class="form-label"
                    class="block text-gray-700 font-medium mb-2">Kategori </label>
                <select name="kategori_id" id="kategori_id"
                    class="form-control w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-300"
                    required>
                    <option value="" disabled {{ old('kategori', $jabatan->kategori_id) ? '' : 'selected' }}>-- Pilih Kategori --</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id', $jabatan->kategori_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors">Ubah</button>
            </div>
        </form>
    </div>
</div>
@endsection

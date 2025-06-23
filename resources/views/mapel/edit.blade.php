@extends('admin.layout')
@section('content')

<div class="w-full p-6 bg-gray-100 min-h-screen">
    <div class="w-full bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-teal-600 p-4">
            <h2 class="text-xl font-semibold text-white">Ubah Mata Pelajaran</h2>
        </div>

        <form method="POST" action="{{ route('mapel.update', $mapel->id) }}" class="p-6">
            @method('PUT')
            @csrf

            <div class="mb-4">
                <label for="nama" class="block text-gray-700 font-medium mb-2">Nama Pelajaran</label>
                <input type="text" name="nama" id="nama"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-300 @error('nama') border-red-500 @enderror"
                    value="{{ old('nama', $mapel->nama ?? '') }}">

                @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tanggal" class="block text-gray-700 font-medium mb-2">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-300 @error('tanggal') border-red-500 @enderror"
                    value="{{ old('tanggal', isset($mapel->tanggal) ? \Carbon\Carbon::parse($mapel->tanggal)->format('Y-m-d') : '') }}">

                @error('tanggal')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="jam_mulai" class="block text-gray-700 font-medium mb-2">Jam Mulai</label>
                <input type="time" name="jam_mulai" id="jam_mulai"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-300 @error('jam_mulai') border-red-500 @enderror"
                    value="{{ old('jam_mulai', isset($mapel->jam_mulai) ? \Carbon\Carbon::createFromFormat('H:i:s', $mapel->jam_mulai)->format('H:i') : '') }}">

                @error('jam_mulai')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <div class="mb-4">
                <label for="jam_selesai" class="block text-gray-700 font-medium mb-2">Jam Selesai</label>
                <input type="time" name="jam_selesai" id="jam_selesai"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-300 @error('jam_selesai') border-red-500 @enderror"
                    value="{{ old('jam_selesai', isset($mapel->jam_selesai) ? \Carbon\Carbon::createFromFormat('H:i:s', $mapel->jam_selesai)->format('H:i') : '') }}">

                @error('jam_selesai')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <div class="flex items-center justify-between">
                <button type="submit"
                    class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors">Ubah</button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('admin.layout')
@section('content')

<div class="w-full p-3 bg-gray-100 min-h-screen">
    <div class="w-full bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-teal-600 p-3">
            <h2 class="text-xl font-semibold text-white">Buat Akun Guru</h2>
        </div>

        <form method="POST" action="{{ route('guru.store') }}" class="p-6">
            @csrf

            <div class="mb-4">
                <label for="nama" class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-300" value="{{ old('nama') }}">
                @error('nama') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="nik" class="block text-gray-700 font-medium mb-2">NIK (16 digit)</label>
                <input type="text" name="nik" id="nik" maxlength="16" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-300" value="{{ old('nik') }}">
                @error('nik') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
             <label for="jenis_kelamin" class="block text-gray-700 font-medium mb-2">Jenis Kelamin</label>
             <select name="jenis_kelamin" id="jenis_kelamin" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-300">
             <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
            <option value="Pria" {{ old('jenis_kelamin') == 'Pria' ? 'selected' : '' }}>Pria</option>
            <option value="Wanita" {{ old('jenis_kelamin') == 'Wanita' ? 'selected' : '' }}>Wanita</option>
            </select>
            @error('jenis_kelamin') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
         </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-300" value="{{ old('email') }}">
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-300">
                @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="no_hp" class="block text-gray-700 font-medium mb-2">No HP</label>
                <input type="text" name="no_hp" id="no_hp" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-300" value="{{ old('no_hp') }}">
                @error('no_hp') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="alamat" class="block text-gray-700 font-medium mb-2">Alamat</label>
                <input type="text" name="alamat" id="alamat" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-300" value="{{ old('alamat') }}">
                @error('alamat') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors">
                    Simpan
                </button>
                <a href="{{ route('guru.index') }}"
                    class="px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white font-medium rounded-lg transition-colors">
                    Batal
                </a>

        </form>
    </div>
</div>
@endsection

@extends('tampil_guru.layout')

@section('content')
    <main class="w-full max-w-3xl mx-auto my-10 px-4 sm:px-6 lg:px-8">
        <section class="bg-white rounded-2xl shadow-lg overflow-hidden bg-gradient-to-br from-teal-50 to-white p-6 sm:p-8 flex flex-col items-center">
            <div class="text-center mb-8">
                <h1 class="text-gray-800 font-bold text-2xl sm:text-3xl mb-3 tracking-tight">Edit Profil</h1>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 w-full max-w-md">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('tampilGuru.update', $guru->id) }}" method="POST" class="w-full max-w-md space-y-5">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="relative">
                    <label class="text-gray-700 font-semibold text-sm mb-1 block">Nama</label>
                    <div class="flex items-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <input type="text" name="nama" required placeholder="Masukkan Nama" value="{{ old('nama', $guru->nama) }}"
                            class="w-full py-2.5 pl-10 pr-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-gray-50 text-sm">
                    </div>
                    @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div class="relative">
                <label class="text-gray-700 font-semibold text-sm mb-1 block">Jenis Kelamin</label>
                <div class="flex items-center relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <select name="jenis_kelamin" class="w-full py-2.5 pl-10 pr-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-gray-50 text-sm">
                        <option value="" disabled>-- Pilih Jenis Kelamin --</option>
                        <option value="Pria" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Pria' ? 'selected' : '' }}>Pria</option>
                        <option value="Wanita" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                    </select>
                </div>
                @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div class="relative">
                    <label class="text-gray-700 font-semibold text-sm mb-1 block">Password <span class="text-gray-400">(kosongkan jika tidak diubah)</span></label>
                    <div class="flex items-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input type="password" name="password" id="passwordInput" placeholder="Masukkan Password Baru"
                            class="w-full py-2.5 pl-10 pr-12 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-gray-50 text-sm">
                        <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- No HP --}}
                <div class="relative">
                    <label class="text-gray-700 font-semibold text-sm mb-1 block">No HP</label>
                    <div class="flex items-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <input type="tel" name="no_hp" required placeholder="Masukkan No HP" value="{{ old('no_hp', $guru->no_hp) }}"
                            class="w-full py-2.5 pl-10 pr-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-gray-50 text-sm">
                    </div>
                    @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Alamat --}}
                <div class="relative">
                    <label class="text-gray-700 font-semibold text-sm mb-1 block">Alamat</label>
                    <div class="flex items-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <textarea name="alamat" required placeholder="Masukkan Alamat"
                            class="w-full py-2.5 pl-10 pr-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-gray-50 h-24 resize-none text-sm">{{ old('alamat', $guru->alamat) }}</textarea>
                    </div>
                    @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="w-full space-y-3">
                    <button type="submit"
                        class="w-full bg-teal-500 py-2.5 rounded-lg text-white font-semibold hover:bg-teal-600 transition-all duration-300 shadow-md flex items-center justify-center">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('guru.info') }}"
                        class="w-full bg-red-500 py-2.5 rounded-lg text-white font-semibold hover:bg-red-600 transition-all duration-300 shadow-md flex items-center justify-center">
                        Batal
                    </a>
                </div>
            </form>
        </section>
    </main>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            icon.setAttribute('stroke', type === 'text' ? 'teal' : 'currentColor');
        });
    </script>
@endsection

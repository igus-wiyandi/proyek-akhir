@extends('tampil_guru.layout')

@section('content')
    <main class="w-full max-w-3xl mx-auto my-10 px-4 sm:px-6 lg:px-8">
        <section class="bg-white rounded-2xl shadow-lg overflow-hidden bg-gradient-to-br from-teal-50 to-white p-6 sm:p-8 flex flex-col items-center transition-all duration-300">
            <div class="text-center mb-8">
                <h1 class="text-gray-800 font-bold text-2xl sm:text-3xl md:text-4xl mb-3 tracking-tight">Edit Akun Anda</h1>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 w-full max-w-md shadow-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('guru.update', $guru->id) }}" method="POST" class="w-full max-w-md space-y-5">
                @csrf
                @method('PUT')

                <div class="relative">
                    <label for="nama" class="text-gray-700 font-semibold text-sm mb-1 block">Nama</label>
                    <div class="flex items-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <input type="text" name="nama" id="nama" required placeholder="Masukkan Nama Anda" value="{{ old('nama', $guru->nama) }}"
                            class="w-full py-2.5 pl-10 pr-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 bg-gray-50 text-gray-800 placeholder-gray-400 text-sm">
                    </div>
                </div>

                <div class="relative">
                    <label for="email" class="text-gray-700 font-semibold text-sm mb-1 block">Email</label>
                    <div class="flex items-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <input type="email" name="email" id="email" required placeholder="Masukkan Email Anda" value="{{ old('email', $guru->email) }}"
                            class="w-full py-2.5 pl-10 pr-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 bg-gray-50 text-gray-800 placeholder-gray-400 text-sm">
                    </div>
                </div>

                <div class="relative">
                    <label for="password" class="text-gray-700 font-semibold text-sm mb-1 block">Password</label>
                    <div class="flex items-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0-1.1.9-2 2-2s2 .9 2 2-2 4-2 4m-4 2H5v-3a3 3 0 013-3h2m0 0c0-1.1.9-2 2-2s2 .9 2 2m-6 7a7 7 0 1114 0H5z" />
                        </svg>
                        <input type="password" name="password" id="passwordInput" placeholder="Masukkan Kata Sandi Baru"
                            class="w-full py-2.5 pl-10 pr-12 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 bg-gray-50 text-gray-800 placeholder-gray-400 text-sm">
                        <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="relative">
                    <label for="no_hp" class="text-gray-700 font-semibold text-sm mb-1 block">No HP</label>
                    <div class="flex items-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <input type="tel" name="no_hp" id="no_hp" required placeholder="Masukkan No HP Anda" value="{{ old('no_hp', $guru->no_hp) }}"
                            class="w-full py-2.5 pl-10 pr-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 bg-gray-50 text-gray-800 placeholder-gray-400 text-sm">
                    </div>
                </div>

                <div class="relative">
                    <label for="alamat" class="text-gray-700 font-semibold text-sm mb-1 block">Alamat</label>
                    <div class="flex items-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <textarea name="alamat" id="alamat" required placeholder="Masukkan Alamat Anda"
                            class="w-full py-2.5 pl-10 pr-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 bg-gray-50 text-gray-800 placeholder-gray-400 h-24 resize-none text-sm">{{ old('alamat', $guru->alamat) }}</textarea>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-teal-500 py-2.5 rounded-lg text-white font-semibold text-base hover:bg-teal-600 transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center">
                    Simpan Perubahan
                </button>
            </form>
        </section>
    </main>

    <script>
        const togglePasswordVisibility = (inputId, iconId) => {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            return () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                icon.classList.toggle('text-teal-500', type === 'text');
                icon.classList.toggle('text-gray-400', type === 'password');
            };
        };

        document.getElementById('togglePassword').addEventListener('click', togglePasswordVisibility('passwordInput', 'eyeIcon'));
    </script>
@endsection

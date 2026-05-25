@extends('tampil_guru.layout')

@section('content')
    <main class="w-full max-w-3xl mx-auto my-10 px-4 sm:px-6 lg:px-8">

        @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
            class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
        @endif

        <section class="bg-white rounded-2xl shadow-lg overflow-hidden bg-gradient-to-br from-teal-50 to-white p-6 sm:p-8 flex flex-col items-center">
            <div class="text-center mb-8">
                <h1 class="text-gray-800 font-bold text-2xl sm:text-3xl mb-3 tracking-tight">Data Pribadi</h1>
            </div>

            <div class="w-full max-w-md space-y-4">

                {{-- Nama --}}
                <div class="bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                    <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Nama</p>
                    <p class="text-gray-800 font-medium">{{ $guru->nama }}</p>
                </div>

                {{-- NIK --}}
                <div class="bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                    <p class="text-xs text-gray-400 font-semibold uppercase mb-1">NIK</p>
                    <p class="text-gray-800 font-medium">{{ $guru->nik ?? '-' }}</p>
                </div>

                {{-- Jenis Kelamin --}}
                <div class="bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                    <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Jenis Kelamin</p>
                    <p class="text-gray-800 font-medium">{{ $guru->jenis_kelamin ?? '-' }}</p>
                </div>

                {{-- Email --}}
                <div class="bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                    <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Email</p>
                    <p class="text-gray-800 font-medium">{{ $guru->user->email ?? '-' }}</p>
                </div>

                {{-- No HP --}}
                <div class="bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                    <p class="text-xs text-gray-400 font-semibold uppercase mb-1">No HP</p>
                    <p class="text-gray-800 font-medium">{{ $guru->no_hp }}</p>
                </div>

                {{-- Alamat --}}
                <div class="bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                    <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Alamat</p>
                    <p class="text-gray-800 font-medium">{{ $guru->alamat }}</p>
                </div>

                {{-- Tombol Edit --}}
                <a href="{{ route('tampilGuru.edit', $guru->id) }}"
                    class="w-full bg-teal-500 py-2.5 rounded-lg text-white font-semibold text-base hover:bg-teal-600 transition-all duration-300 shadow-md flex items-center justify-center mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Profil
                </a>

            </div>
        </section>
    </main>
@endsection

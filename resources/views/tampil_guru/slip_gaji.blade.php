@extends('tampil_guru.layout') @section('content')
<div class="w-full max-w-4xl mx-auto p-4 md:p-6 min-h-screen">

    <div class="flex items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Slip Gaji Anda</h2>
    </div>

    @if(!$gajiTerbaru)
    <div class="bg-white rounded-xl shadow-sm p-8 text-center border border-gray-100">
        <div class="text-4xl mb-3">📄</div>
        <h3 class="text-lg font-bold text-gray-700">Belum Ada Slip Gaji</h3>
        <p class="text-gray-500 text-sm mt-1">Slip gaji Anda akan muncul di sini setelah diterbitkan oleh pihak sekolah.</p>
    </div>
    @else
    <h3 class="text-lg font-bold text-gray-800 mb-3">Slip Gaji Terbaru</h3>
    <div class="bg-white rounded-2xl shadow-sm border border-blue-50 p-5 md:p-6 mb-8 flex flex-col md:flex-row justify-between items-center gap-4 hover:shadow-md transition-shadow relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -z-10 opacity-50"></div>

        <div class="flex items-center gap-5 w-full">
            <div class="w-14 h-14 rounded-xl bg-blue-500 text-white flex items-center justify-center text-2xl shadow-inner shrink-0">
                📄
            </div>
            <div>
                <h4 class="text-lg font-bold text-gray-900">{{ $gajiTerbaru->nama_periode }}</h4>
                <p class="text-sm text-gray-500 mt-0.5">Diterbitkan pada {{ $gajiTerbaru->created_at->format('d M Y') }}</p>
                <span class="inline-block mt-2 bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-md font-bold tracking-wide">
                    Tersedia
                </span>
            </div>
        </div>

        <a href="{{ route('guru.slip_gaji.show', $gajiTerbaru->id) }}" class="w-full md:w-auto text-center border-2 border-blue-100 text-blue-600 hover:bg-blue-50 hover:border-blue-200 p-3 rounded-xl transition-colors shrink-0 font-bold flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            <span class="md:hidden">Lihat Detail</span>
        </a>
    </div>

    @if($riwayatLama->isNotEmpty())
    <h3 class="text-lg font-bold text-gray-800 mb-3">Riwayat Slip Gaji</h3>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-100">

        @foreach($riwayatLama as $lama)
        <div class="p-5 flex flex-col md:flex-row justify-between items-center gap-4 hover:bg-gray-50 transition-colors">
            <div class="w-full">
                <h4 class="text-base font-bold text-gray-800">{{ $lama->nama_periode }}</h4>
                <div class="flex items-center gap-3 mt-1">
                    <p class="text-xs text-gray-500">Diterbitkan pada {{ $lama->created_at->format('d M Y') }}</p>
                    <span class="bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded font-bold">Tersedia</span>
                </div>
            </div>

            <a href="{{ route('guru.slip_gaji.show', $lama->id) }}" class="w-full md:w-auto text-center border-2 border-gray-100 text-blue-600 hover:bg-blue-50 p-2.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span class="md:hidden text-sm font-semibold">Lihat</span>
            </a>
        </div>
        @endforeach

    </div>
    @endif
    @endif
</div>
@endsection
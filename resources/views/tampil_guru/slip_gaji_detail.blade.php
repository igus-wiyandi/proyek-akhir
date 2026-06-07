@extends('tampil_guru.layout')
@section('content')
<div class="w-full max-w-md mx-auto bg-gray-50 min-h-screen pb-10">

    <div class="bg-white px-4 py-4 flex items-center justify-between sticky top-0 z-10 shadow-sm">
        <a href="{{ route('guru.slip_gaji') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-lg font-bold text-gray-800">Detail Slip Gaji</h1>
        <a href="{{ route('guru.slip_gaji.download', $gaji->id) }}" class="text-blue-600 hover:text-blue-800" title="Download PDF">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
        </a>
    </div>

    <div class="text-center pt-6 pb-4">
        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-bold tracking-wide">Tersedia</span>
        <h2 class="text-2xl font-bold text-gray-900 mt-3">{{ $gaji->nama_periode }}</h2>
        <p class="text-sm text-gray-500 mt-1">Diterima pada {{ $gaji->created_at->format('d M Y') }}</p>
    </div>

    @php
    // Kalkulasi Rupiah untuk UI
    $pendapatanPokok = ($gaji->total_jam_bersih + $gaji->total_potongan_jam) * $gaji->tarif_per_jam;
    $totalPendapatanKotor = $pendapatanPokok + $gaji->tunjangan_tambahan;
    $potonganAbsen = $gaji->total_potongan_jam * $gaji->tarif_per_jam;

    // Tarik nama jabatan dari tabel kategori melalui relasi tabel jabatan
    $daftarJabatan = \App\Models\Jabatan::join('kategori', 'jabatan.kategori_id', '=', 'kategori.id')
    ->where('jabatan.guru_id', $gaji->guru_id)
    ->pluck('kategori.nama')
    ->toArray();

    // Jika kosong (tidak ada tugas tambahan), tampilkan Tenaga Pendidik.
    // Jika ada, gabungkan namanya (contoh: "Guru / Pembina UKS, Pustakawan")
    $teksJabatan = empty($daftarJabatan) ? 'Tenaga Pendidik' : implode(', ', $daftarJabatan);
    @endphp

    <div class="bg-white mx-4 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="p-5 border-b border-dashed border-gray-200">
            <div class="text-center mb-6">
                <h3 class="text-lg font-bold text-blue-800 uppercase">Pondok pesantren Nurul Ihsan</h3>
                <p class="text-xs text-gray-500 font-semibold uppercase">Slip Gaji <br> {{ $gaji->nama_periode }}</p>
            </div>

            <table class="w-full text-sm text-gray-700">
                <tr>
                    <td class="py-1 w-24">Nama</td>
                    <td class="py-1">: {{ $gaji->guru->nama }}</td>
                </tr>
                <tr>
                    <td class="py-1">NIK</td>
                    <td class="py-1">: {{ $gaji->guru->nik ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1">Jabatan</td>
                    <td class="py-1 font-semibold text-gray-800">: {{ $teksJabatan }}</td>
                </tr>
            </table>
        </div>

        <div class="p-5 border-b border-dashed border-gray-200">
            <h4 class="text-xs font-bold text-gray-800 mb-3 tracking-wider uppercase">Pendapatan</h4>
            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Gaji Pokok ({{ $gaji->total_jam_bersih }} Jam)</span>
                    <span>Rp {{ number_format($pendapatanPokok, 0, ',', '.') }}</span>
                </div>
                @if($gaji->tunjangan_tambahan > 0)
                <div class="flex justify-between">
                    <span>Tunjangan Tugas Tambahan</span>
                    <span>Rp {{ number_format($gaji->tunjangan_tambahan, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between font-bold text-gray-800 pt-2 mt-2 border-t border-gray-100">
                    <span>Total Pendapatan</span>
                    <span>Rp {{ number_format($totalPendapatanKotor, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="p-5">
            <h4 class="text-xs font-bold text-gray-800 mb-3 tracking-wider uppercase">Potongan</h4>
            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Absensi ({{ $gaji->total_potongan_jam }} Jam)</span>
                    <span>Rp {{ number_format($potonganAbsen, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-800 pt-2 mt-2 border-t border-gray-100">
                    <span>Total Potongan</span>
                    <span>Rp {{ number_format($potonganAbsen, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 p-5 flex justify-between items-center border-t border-blue-100">
            <span class="font-bold text-gray-800 uppercase text-sm">Total Diterima</span>
            <span class="font-bold text-blue-600 text-lg">Rp {{ number_format($gaji->total_gaji_bersih, 0, ',', '.') }}</span>
        </div>
    </div>

</div>
@endsection
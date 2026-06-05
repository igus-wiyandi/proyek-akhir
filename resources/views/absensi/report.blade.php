@extends($isAdmin ? 'admin.layout' : 'tampil_guru.layout')
@section('content')
<div class="w-full p-6 bg-gray-100 min-h-screen">

    <div class="w-full bg-white rounded-lg shadow-md overflow-hidden mb-6 print:hidden">
        <div class="bg-teal-600 p-4">
            <h2 class="text-xl font-semibold text-white">Filter Laporan Rekap Absensi</h2>
        </div>

        <form method="GET" action="{{ route('absensi.report') }}" class="p-6 bg-white">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full text-sm border-gray-300 rounded focus:border-teal-500 py-2 px-3">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full text-sm border-gray-300 rounded focus:border-teal-500 py-2 px-3">
                </div>

                @if($isAdmin)
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">Pilih Guru</label>
                    <select name="guru_id" class="w-full text-sm border-gray-300 rounded focus:border-teal-500 py-2 px-3">
                        <option value="">-- Semua Guru --</option>
                        @foreach($listGuru as $g)
                        <option value="{{ $g->id }}" {{ $selectedGuru == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">Status Kehadiran</label>
                    <select name="status" class="w-full text-sm border-gray-300 rounded focus:border-teal-500 py-2 px-3">
                        <option value="">-- Semua Status --</option>
                        <option value="Hadir" {{ $selectedStatus == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Setengah Hari" {{ $selectedStatus == 'Setengah Hari' ? 'selected' : '' }}>Setengah Hari</option>
                        <option value="Alpa" {{ $selectedStatus == 'Alpa' ? 'selected' : '' }}>Alpa</option>
                    </select>
                </div>

                <div class="flex gap-2 {{ $isAdmin ? 'md:col-span-4 justify-end' : 'md:col-span-1 mt-4' }}">
                    <button type="submit" class="bg-teal-600 text-white px-5 py-2 rounded text-sm font-semibold hover:bg-teal-700 transition shadow-sm">
                        Tampilkan Laporan
                    </button>
                    <a href="{{ route('absensi.report') }}" class="bg-gray-400 text-white px-5 py-2 rounded text-sm font-semibold hover:bg-gray-500 transition shadow-sm">
                        Reset
                    </a>

                    <a href="{{ route('absensi.report.excel', request()->all()) }}" class="bg-green-600 text-white px-5 py-2 rounded text-sm font-semibold hover:bg-green-700 transition shadow-sm flex items-center gap-1">
                        📊 Excel
                    </a>

                    <a href="{{ route('absensi.report.pdf', request()->all()) }}" class="bg-red-600 text-white px-5 py-2 rounded text-sm font-semibold hover:bg-red-700 transition shadow-sm flex items-center gap-1">
                        📄 PDF
                    </a>

                    <button type="button" onclick="window.print()" class="bg-blue-600 text-white px-5 py-2 rounded text-sm font-semibold hover:bg-blue-700 transition shadow-sm flex items-center gap-1">
                        🖨️ Cetak
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="w-full bg-white rounded-lg shadow-md p-8 border border-gray-200 print:shadow-none print:border-0 print:p-0">

        <div class="text-center mb-6 border-b-2 border-gray-800 pb-4">
            <h1 class="text-2xl font-bold text-gray-900 uppercase">Laporan Rekapitulasi Absensi Guru</h1>
            <p class="text-sm text-gray-600 mt-1">
                Periode Tanggal: <span class="font-semibold">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</span> s/d <span class="font-semibold">{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-100 print:bg-gray-200">
                        <th class="p-3 border border-gray-300 text-left w-12 text-gray-800 font-semibold">No</th>
                        <th class="p-3 border border-gray-300 text-left text-gray-800 font-semibold">Nama Guru</th>
                        <th class="p-3 border border-gray-300 text-center w-36 text-gray-800 font-semibold">Tanggal</th>
                        <th class="p-3 border border-gray-300 text-center w-40 text-gray-800 font-semibold">Jam Scan</th>
                        <th class="p-3 border border-gray-300 text-center w-36 text-gray-800 font-semibold">Status Kehadiran</th>
                        <th class="p-3 border border-gray-300 text-center w-28 text-gray-800 font-semibold">Potongan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $index => $report)
                    <tr class="hover:bg-gray-50 border-b border-gray-200">
                        <td class="p-3 border border-gray-300 text-gray-700 text-center">{{ $index + 1 }}</td>
                        <td class="p-3 border border-gray-300 text-gray-900 font-medium">{{ $report->guru->nama ?? 'Data Guru Dihapus' }}</td>
                        <td class="p-3 border border-gray-300 text-gray-700 text-center">{{ \Carbon\Carbon::parse($report->tanggal)->format('d M Y') }}</td>
                        <td class="p-3 border border-gray-300 text-gray-700 text-center">
                            {{ $report->jam_masuk ?? '-' }} s/d {{ $report->jam_pulang ?? '-' }}
                        </td>

                        <td class="p-3 border border-gray-300 text-center">
                            @php
                            $status = $report->status_kehadiran;
                            $bgColor = 'bg-gray-100 text-gray-800';
                            if ($status == 'Hadir') $bgColor = 'bg-green-100 text-green-800 print:bg-transparent print:text-black';
                            elseif ($status == 'Setengah Hari') $bgColor = 'bg-yellow-100 text-yellow-800 print:bg-transparent print:text-black';
                            elseif ($status == 'Alpa' || str_contains($status, 'Tidak')) $bgColor = 'bg-red-100 text-red-800 print:bg-transparent print:text-black';
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $bgColor }}">
                                {{ $status }}
                            </span>
                        </td>

                        <td class="p-3 border border-gray-300 text-center font-semibold {{ $report->potongan_jam > 0 ? 'text-red-600 print:text-black' : 'text-gray-500' }}">
                            {{ $report->potongan_jam }} Jam
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500 font-medium bg-gray-50 border border-gray-300">
                            Tidak ditemukan riwayat data absensi pada rentang filter ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="hidden print:block mt-12">
            <div class="flex justify-end">
                <div class="text-center w-64">
                    <p class="text-sm">Majalengka, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                    <p class="text-sm font-semibold mt-1 mb-20">Kepala Madrasah,</p>
                    <p class="text-sm font-bold border-b border-gray-800 pb-1">_______________________</p>
                    <p class="text-xs text-gray-500 mt-1">NIP. ..................................</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
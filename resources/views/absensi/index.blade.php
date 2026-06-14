@extends($isAdmin ? 'admin.layout' : 'tampil_guru.layout')
@section('content')
<div class="w-full p-6 bg-gray-100 ">
    <div class="w-full bg-white rounded-lg shadow-md overflow-hidden">

        @if (session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition
            class="flex justify-center mt-4 bg-teal-100 border border-teal-400 text-teal-700 px-4 py-3 rounded relative mx-6">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="flex justify-center mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-6">
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-teal-600 p-4 flex justify-between items-center mt-4">
            <h2 class="text-xl font-semibold text-white">Data Kehadiran & Absensi</h2>
            @if($isAdmin)
            <a href="{{ route('absensi.create') }}" class="text-white hover:text-teal-200 transition-colors flex items-center gap-2" title="Import Data">
                <span class="text-sm font-medium">Import Excel</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </a>
            @endif
        </div>

        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-green-50 border border-green-200 p-4 rounded-lg flex items-center justify-between shadow-sm">
                    <div>
                        <p class="text-sm text-green-600 font-semibold">Total Hadir</p>
                        <p class="text-2xl font-bold text-green-800">{{ $totalHadir ?? 0 }} <span class="text-sm font-normal">Hari</span></p>
                    </div>
                    <div class="p-3 bg-green-200 rounded-full text-green-700 font-bold text-xl">✓</div>
                </div>

                <div class="bg-red-50 border border-red-200 p-4 rounded-lg flex items-center justify-between shadow-sm">
                    <div>
                        <p class="text-sm text-red-600 font-semibold">Total Alpa / Tdk Sah</p>
                        <p class="text-2xl font-bold text-red-800">{{ $totalAlpa ?? 0 }} <span class="text-sm font-normal">Hari</span></p>
                    </div>
                    <div class="p-3 bg-red-200 rounded-full text-red-700 font-bold text-xl">✗</div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg flex items-center justify-between shadow-sm">
                    <div>
                        <p class="text-sm text-yellow-600 font-semibold">Akumulasi Potongan</p>
                        <p class="text-2xl font-bold text-yellow-800">{{ $totalPotongan ?? 0 }} <span class="text-sm font-normal">Jam</span></p>
                    </div>
                    <div class="p-3 bg-yellow-200 rounded-full text-yellow-700 font-bold text-xl">⌚</div>
                </div>
            </div>

            <form method="GET" action="{{ route('absensi.index') }}" class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 block mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="w-full text-sm border-gray-300 rounded focus:border-teal-500 py-2 px-3">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 block mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="w-full text-sm border-gray-300 rounded focus:border-teal-500 py-2 px-3">
                    </div>

                    @if($isAdmin)
                    <div>
                        <label class="text-xs font-semibold text-gray-600 block mb-1">Pilih Guru</label>
                        <select name="guru_id" class="w-full text-sm border-gray-300 rounded focus:border-teal-500 py-2 px-3">
                            <option value="">-- Semua Guru --</option>
                            @isset($listGuru)
                            @foreach($listGuru as $g)
                            <option value="{{ $g->id }}" {{ (isset($selectedGuru) && $selectedGuru == $g->id) ? 'selected' : '' }}>{{ $g->nama }}</option>
                            @endforeach
                            @endisset
                        </select>
                    </div>
                    @endif

                    <div>
                        <label class="text-xs font-semibold text-gray-600 block mb-1">Status Kehadiran</label>
                        <select name="status" class="w-full text-sm border-gray-300 rounded focus:border-teal-500 py-2 px-3">
                            <option value="">-- Semua Status --</option>
                            <option value="Hadir" {{ (isset($selectedStatus) && $selectedStatus == 'Hadir') ? 'selected' : '' }}>Hadir</option>
                            <option value="Setengah Hari" {{ (isset($selectedStatus) && $selectedStatus == 'Setengah Hari') ? 'selected' : '' }}>Setengah Hari</option>
                            <option value="Alpa" {{ (isset($selectedStatus) && $selectedStatus == 'Alpa') ? 'selected' : '' }}>Alpa</option>
                        </select>
                    </div>

                    <div class="flex gap-2 {{ $isAdmin ? 'md:col-span-4 justify-end' : 'md:col-span-1' }}">
                        <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded text-sm hover:bg-teal-700 transition">Terapkan</button>
                        <a href="{{ route('absensi.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded text-sm hover:bg-gray-500 transition">Reset</a>
                    </div>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-teal-50">
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200 text-sm">No</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200 text-sm">Nama</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200 text-sm">Tanggal</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200 text-sm">Scan (In - Out)</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200 text-sm">Status</th>
                            <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200 text-sm">Potongan</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $no = ($absensi->currentPage() - 1) * $absensi->perPage() + 1; ?>

                        @forelse ($absensi as $absensis)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b border-gray-200 text-sm text-gray-700">
                                {{ $no++ }}
                            </td>

                            <td class="p-3 border-b border-gray-200 text-sm text-gray-700 font-medium">
                                {{ $absensis->guru->nama ?? 'Data Guru Dihapus' }}
                            </td>

                            <td class="p-3 border-b border-gray-200 text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($absensis->tanggal)->format('d M Y') }}
                            </td>

                            <td class="p-3 border-b border-gray-200 text-sm text-gray-700">
                                {{ $absensis->jam_masuk }} - {{ $absensis->jam_pulang }}
                            </td>

                            <td class="p-3 border-b border-gray-200">
                                @php
                                $status = $absensis->status_kehadiran;
                                $bgColor = 'bg-gray-100 text-gray-800'; // Default

                                if ($status == 'Hadir') {
                                $bgColor = 'bg-green-100 text-green-800';
                                } elseif ($status == 'Setengah Hari') {
                                $bgColor = 'bg-yellow-100 text-yellow-800';
                                } elseif ($status == 'Alpa' || str_contains($status, 'Tidak')) {
                                $bgColor = 'bg-red-100 text-red-800';
                                }
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full inline-block {{ $bgColor }}">
                                    {{ $status }}
                                </span>
                            </td>

                            <td class="p-3 border-b border-gray-200 text-sm font-semibold {{ $absensis->potongan_jam > 0 ? 'text-red-600' : 'text-gray-500' }}">
                                {{ $absensis->potongan_jam }} Jam
                            </td>


                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-gray-500 font-medium bg-gray-50">
                                Belum ada data absensi yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $absensi->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
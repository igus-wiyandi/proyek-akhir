@extends ($isAdmin ? 'admin.layout' : 'tampil_guru.layout')
@push('styles')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@section('content')
<div class="w-full p-6 bg-gray-100">
    <div class="w-full bg-white rounded-lg shadow-md overflow-hidden">

        @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition class="flex justify-center mt-4 mx-4 bg-teal-100 text-teal-800 p-3 rounded font-medium">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-teal-600 p-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Riwayat Penggajian</h2>
            @if($isAdmin)
            <a href="{{ route('perhitungan_gaji.index') }}" class="bg-white text-teal-700 px-4 py-2 rounded text-sm font-bold hover:bg-gray-100">
                + Generate Gaji
            </a>
            @endif
        </div>

        <div class="p-6 bg-gray-50 border-b">
            <form method="GET" action="{{ route('perhitungan_gaji.laporanGaji') }}" class="flex items-end gap-4">
                <div>
                    <label class="text-xs font-semibold text-gray-600 block mb-1">Mulai Periode</label>
                    <input type="date" name="start" value="{{ request('start') }}" class="text-sm border-gray-300 rounded focus:border-teal-500 py-2 px-3" required>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 block mb-1">Akhir Periode</label>
                    <input type="date" name="end" value="{{ request('end') }}" class="text-sm border-gray-300 rounded focus:border-teal-500 py-2 px-3" required>
                </div>
                <div class="flex gap-2 items-end">
                    <button type="submit" class="bg-teal-700 text-white px-6 py-2 rounded text-sm font-semibold hover:bg-teal-800 transition">Cari RiwayatT</button>

                    @if(request('start') && request('end') && !$dataGaji->isEmpty())
                    <a href="{{ route('perhitungan_gaji.excel', request()->all()) }}" class="bg-green-600 text-white px-4 py-2 rounded text-sm font-semibold hover:bg-green-700 transition flex items-center gap-1 shadow-sm">
                        📊 Excel
                    </a>
                    <a href="{{ route('perhitungan_gaji.pdf', request()->all()) }}" class="bg-red-600 text-white px-4 py-2 rounded text-sm font-semibold hover:bg-red-700 transition flex items-center gap-1 shadow-sm">
                        📄 PDF
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto p-6">
            <table class="w-full border-collapse">
                <thead class="bg-teal-50">
                    <tr>
                        <th class="p-3 text-left text-sm text-teal-800 border-b">Periode</th>
                        <th class="p-3 text-left text-sm text-teal-800 border-b">Nama Guru</th>
                        <th class="p-3 text-center text-sm text-teal-800 border-b">Jam Bersih</th>
                        <th class="p-3 text-center text-sm text-teal-800 border-b">Potongan</th>
                        <th class="p-3 text-right text-sm text-teal-800 border-b">Total Gaji</th>
                        <th class="p-3 text-center text-sm text-teal-800 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($dataGaji->isEmpty())
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500 bg-gray-50">Pilih rentang tanggal untuk menampilkan riwayat.</td>
                    </tr>
                    @else
                    @foreach ($dataGaji as $gaji)
                <tbody x-data="{ open: false }">
                    <tr class="hover:bg-gray-50 border-b border-gray-100 transition-colors">
                        <td class="p-3 text-sm">{{ $gaji->nama_periode }}</td>
                        <td class="p-3 text-sm font-bold text-gray-800">{{ $gaji->guru->nama ?? '-' }}</td>
                        <td class="p-3 text-sm text-center font-bold text-green-600">{{ $gaji->total_jam_bersih }} Jam</td>
                        <td class="p-3 text-sm text-center {{ $gaji->total_potongan_jam > 0 ? 'text-red-600 font-bold' : 'text-gray-500' }}">{{ $gaji->total_potongan_jam }} Jam</td>
                        <td class="p-3 text-sm text-right font-bold text-teal-700">Rp {{ number_format($gaji->total_gaji_bersih, 0, ',', '.') }}</td>
                        <td class="p-3 text-center">
                            <button @click="open = !open" class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs font-bold hover:bg-blue-200">
                                <span x-text="open ? 'Tutup Info' : 'Info'"></span>
                            </button>
                        </td>
                    </tr>

                    <tr x-show="open" x-transition class="bg-gray-50">
                        <td colspan="6" class="p-4 border-b border-gray-200">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                <div class="bg-white p-3 border rounded shadow-sm lg:col-span-1">
                                    <h4 class="text-sm font-bold text-teal-800 mb-2">Ringkasan Pendapatan</h4>
                                    <ul class="text-xs space-y-2 text-gray-700">
                                        <li class="flex justify-between"><span>Gaji Mengajar ({{ $gaji->total_jam_bersih }} Jam)</span> <span class="font-semibold">Rp {{ number_format($gaji->total_jam_bersih * 45000, 0, ',', '.') }}</span></li>
                                        <li class="flex justify-between border-b pb-2"><span>Tunjangan Tugas</span> <span class="font-semibold">Rp {{ number_format($gaji->tunjangan_tambahan, 0, ',', '.') }}</span></li>
                                        <li class="flex justify-between font-bold text-sm pt-1"><span>Total Diterima</span> <span class="text-teal-700">Rp {{ number_format($gaji->total_gaji_bersih, 0, ',', '.') }}</span></li>
                                    </ul>
                                </div>

                                <div class="bg-white border rounded shadow-sm overflow-hidden lg:col-span-2">
                                    <div class="bg-teal-50 px-3 py-2 font-bold text-teal-800 text-sm border-b">Detail Perhitungan Per Minggu</div>
                                    <table class="w-full text-xs text-left">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="p-2 border-b">Minggu Ke-</th>
                                                <th class="p-2 border-b text-center text-red-600">Potongan</th>
                                                <th class="p-2 border-b text-center text-green-600">Jam Bersih</th>
                                                <th class="p-2 border-b text-right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($gaji->rincian_mingguan as $minggu => $detail)
                                            <tr>
                                                <td class="p-2 border-b font-medium">
                                                    {{ $minggu }}
                                                    @if(!empty($detail['detail_absen']))
                                                    <ul class="text-[10px] text-red-500 list-disc pl-4 mt-1">
                                                        @foreach($detail['detail_absen'] as $hari) <li>{{ $hari }}</li> @endforeach
                                                    </ul>
                                                    @endif
                                                </td>
                                                <td class="p-2 border-b text-center font-semibold {{ $detail['potongan_jam'] > 0 ? 'text-red-600' : 'text-gray-400' }}">{{ $detail['potongan_jam'] }} Jam</td>
                                                <td class="p-2 border-b text-center font-bold text-green-600">{{ $detail['jam_bersih'] }} Jam</td>
                                                <td class="p-2 border-b text-right text-gray-800 font-semibold">Rp {{ number_format($detail['gaji_mingguan'], 0, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
                @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
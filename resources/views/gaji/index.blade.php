@extends ($isAdmin ? 'admin.layout' : 'tampil_guru.layout')
@push('styles')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@section('content')
<div class="w-full p-6 bg-gray-100">
    <div class="w-full bg-white rounded-lg shadow-md overflow-hidden">

        <div class="bg-teal-600 p-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Generate Penggajian Baru</h2>
            <a href="{{ route('perhitungan_gaji.index') }}" class="text-teal-100 hover:text-white text-sm font-semibold">&larr; Kembali ke Riwayat</a>
        </div>

        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 m-4 rounded relative">{{ session('error') }}</div>
        @endif

        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <form method="POST" action="{{ route('perhitungan_gaji.preview') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf
                <div class="md:col-span-2">
                    <label class="text-xs font-bold text-gray-700 block mb-1">Nama Periode Penggajian</label>
                    <input type="text" name="nama_periode" value="{{ $requestData['nama_periode'] ?? old('nama_periode') }}" placeholder="Misal: Gaji April 2026" class="w-full text-sm border-gray-300 rounded focus:border-teal-500 py-2 px-3" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">Dari Tanggal Absensi</label>
                    <input type="date" name="start" value="{{ $requestData['start'] ?? old('start') }}" class="w-full text-sm border-gray-300 rounded focus:border-teal-500 py-2 px-3" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">Sampai Tanggal</label>
                    <input type="date" name="end" value="{{ $requestData['end'] ?? old('end') }}" class="w-full text-sm border-gray-300 rounded focus:border-teal-500 py-2 px-3" required>
                </div>
                <div class="md:col-span-4 mt-2">
                    <button type="submit" class="bg-teal-700 text-white px-6 py-2 rounded font-semibold hover:bg-teal-800 w-full md:w-auto">1. Kalkulasi / Preview Gaji</button>
                </div>
            </form>
        </div>

        @isset($previewGaji)
        <div class="p-6">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                <p class="text-sm text-yellow-800 font-bold">MODE PREVIEW: Data di bawah ini belum tersimpan. Harap cek kembali angkanya. Jika sudah benar, klik tombol "Simpan ke Database" di bagian paling bawah tabel.</p>
            </div>

            <form method="POST" action="{{ route('perhitungan_gaji.store') }}">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse mb-6">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left text-sm text-gray-700 border-b">Nama Guru</th>
                                <th class="p-3 text-center text-sm text-gray-700 border-b">Jam Bersih</th>
                                <th class="p-3 text-center text-sm text-gray-700 border-b">Potongan</th>
                                <th class="p-3 text-right text-sm text-gray-700 border-b">Tunjangan</th>
                                <th class="p-3 text-right text-sm text-gray-700 border-b">Total Gaji</th>
                                <th class="p-3 text-center text-sm text-gray-700 border-b">Aksi</th>
                            </tr>
                        </thead>

                        @foreach ($previewGaji as $index => $gaji)
                        <tbody x-data="{ open: false }">
                            <tr class="hover:bg-gray-50 border-b transition-colors">
                                <td class="p-3 text-sm font-bold text-gray-800">{{ $gaji['nama_guru'] }}</td>
                                <td class="p-3 text-sm text-center font-bold text-green-600">{{ $gaji['jam_bersih'] }} Jam</td>
                                <td class="p-3 text-sm text-center {{ $gaji['total_potongan_jam'] > 0 ? 'text-red-600 font-bold' : 'text-gray-500' }}">{{ $gaji['total_potongan_jam'] }} Jam</td>
                                <td class="p-3 text-sm text-right text-gray-700">Rp {{ number_format($gaji['tunjangan_tambahan'], 0, ',', '.') }}</td>
                                <td class="p-3 text-sm text-right font-bold text-teal-700">Rp {{ number_format($gaji['total_gaji_bersih'], 0, ',', '.') }}</td>
                                <td class="p-3 text-center">
                                    <button type="button" @click="open = !open" class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs font-bold hover:bg-blue-200">
                                        <span x-text="open ? 'Tutup Info' : 'Info'"></span>
                                    </button>
                                </td>
                            </tr>

                            <tr x-show="open" x-transition class="bg-gray-50">
                                <td colspan="6" class="p-4 border-b border-gray-200">
                                    <div class="bg-white border rounded shadow-sm overflow-hidden">
                                        <div class="bg-teal-50 px-3 py-2 font-bold text-teal-800 text-sm border-b">Detail Perhitungan Mengajar (Mingguan)</div>
                                        <table class="w-full text-xs text-left">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="p-2 border-b">Periode</th>
                                                    <th class="p-2 border-b text-center">Standar</th>
                                                    <th class="p-2 border-b text-center text-red-600">Potongan</th>
                                                    <th class="p-2 border-b text-center text-green-600">Bersih</th>
                                                    <th class="p-2 border-b text-right">Gaji Subtotal (x 45.000)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                $rincianJson = json_decode($gaji['rincian_mingguan'], true);
                                                @endphp
                                                @foreach($rincianJson as $minggu => $detail)
                                                <tr>
                                                    <td class="p-2 border-b font-medium">
                                                        {{ $minggu }}
                                                        @if(!empty($detail['detail_absen']))
                                                        <ul class="text-[10px] text-red-500 list-disc pl-4 mt-1">
                                                            @foreach($detail['detail_absen'] as $hari) <li>{{ $hari }}</li> @endforeach
                                                        </ul>
                                                        @endif
                                                    </td>
                                                    <td class="p-2 border-b text-center text-gray-500">{{ $detail['jam_standar'] }} Jam</td>
                                                    <td class="p-2 border-b text-center font-semibold {{ $detail['potongan_jam'] > 0 ? 'text-red-600' : 'text-gray-400' }}">{{ $detail['potongan_jam'] }} Jam</td>
                                                    <td class="p-2 border-b text-center font-bold text-green-600">{{ $detail['jam_bersih'] }} Jam</td>
                                                    <td class="p-2 border-b text-right text-gray-800 font-semibold">Rp {{ number_format($detail['gaji_mingguan'], 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>

                        <input type="hidden" name="data[{{ $index }}][guru_id]" value="{{ $gaji['guru_id'] }}">
                        <input type="hidden" name="data[{{ $index }}][nama_periode]" value="{{ $gaji['nama_periode'] }}">
                        <input type="hidden" name="data[{{ $index }}][periode_mulai]" value="{{ $gaji['periode_mulai'] }}">
                        <input type="hidden" name="data[{{ $index }}][periode_akhir]" value="{{ $gaji['periode_akhir'] }}">
                        <input type="hidden" name="data[{{ $index }}][jam_bersih]" value="{{ $gaji['jam_bersih'] }}">
                        <input type="hidden" name="data[{{ $index }}][total_potongan_jam]" value="{{ $gaji['total_potongan_jam'] }}">
                        <input type="hidden" name="data[{{ $index }}][tunjangan_tambahan]" value="{{ $gaji['tunjangan_tambahan'] }}">
                        <input type="hidden" name="data[{{ $index }}][total_gaji_bersih]" value="{{ $gaji['total_gaji_bersih'] }}">
                        <input type="hidden" name="data[{{ $index }}][rincian_mingguan]" value="{{ $gaji['rincian_mingguan'] }}">
                        @endforeach
                    </table>
                </div>

                <div class="flex justify-end bg-gray-50 p-4 border rounded">
                    <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-bold text-lg hover:bg-green-700 shadow-lg">2. Simpan ke Database</button>
                </div>
            </form>
        </div>
        @endisset
    </div>
</div>
@endsection
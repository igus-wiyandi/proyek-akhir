@extends('admin.layout')
@section('content')
<div class="w-full bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-teal-600 p-3">
        <h2 class="text-xl font-semibold text-white">Import Data Absen</h2>
    </div>

    <form method="POST" action="{{ route('absensi.preview') }}" enctype="multipart/form-data" class="p-6">
        @csrf
        <div class="max-w-md mx-auto">

            <div class="flex gap-4 mb-4">
                <div class="w-1/3">
                    <label class="text-base text-slate-900 font-medium mb-2 block">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" required value="{{ old('tanggal_mulai') }}"
                        class="w-full text-slate-700 font-medium text-sm bg-white border border-gray-300 py-2 px-3 rounded focus:outline-none focus:border-teal-500" />
                </div>
                <div class="w-1/3">
                    <label class="text-base text-slate-900 font-medium mb-2 block">Tanggal Akhir <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_akhir" required value="{{ old('tanggal_akhir') }}"
                        class="w-full text-slate-700 font-medium text-sm bg-white border border-gray-300 py-2 px-3 rounded focus:outline-none focus:border-teal-500" />
                </div>
                <div class="w-1/3">
                    <label class="text-base text-slate-900 font-medium mb-2 block">Tgl Libur (Opsional)</label>
                    <div id="container-libur" class="flex flex-col gap-2">
                        <input type="date" name="tanggal_libur[]"
                            class="w-full text-slate-700 font-medium text-sm bg-white border border-gray-300 py-2 px-3 rounded focus:outline-none focus:border-teal-500" />
                    </div>
                    <button type="button" onclick="tambahLibur()" class="text-xs text-teal-600 mt-2 font-semibold hover:underline">
                        + Tambah Hari Libur
                    </button>
                </div>
            </div>

            <label class="text-base text-slate-900 font-medium mb-3 block">Upload file Absensi</label>
            <input type="file"
                name="file"
                class="w-full text-slate-500 font-medium text-sm bg-white border file:cursor-pointer cursor-pointer file:border-0 file:py-3 file:px-4 file:mr-4 file:bg-gray-100 file:hover:bg-gray-200 file:text-slate-500 rounded"
                accept=".xls,.xlsx" />
            <p class="text-xs text-slate-500 mt-2">Hanya File Excel (XLS, XLSX) yang Diupload.</p>
        </div>

        <div class="max-w-md mx-auto">
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded mt-4">Preview</button>
            <a href="{{ route('absensi.create') }}">
                <button type="button" class="bg-red-600 text-white px-6 py-2 rounded mt-4">Reset</button>
            </a>
        </div>
    </form>

    @if ($errors->any())
    <div class="bg-red-200 text-red-800 p-3 rounded mt-4 mb-4 mx-6">
        <strong>Peringatan!</strong>
        <ul class="list-disc list-inside mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-teal-200 text-teal-800 p-3 rounded m-4 mx-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-200 text-red-800 p-3 rounded m-4 mx-6">
        <strong>Terjadi Kesalahan:</strong> {{ session('error') }}
    </div>
    @endif

    @isset($dataAbsensi)
    <form method="POST" action="{{ route('absensi.store') }}" class="p-6 pt-0">
        @csrf

        <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
        <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">

        <table class="w-full border-collapse mt-6">
            <thead>
                <tr class="bg-teal-50">
                    <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">No</th>
                    <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Nama</th>
                    <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Tanggal</th>
                    <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Scan absensi</th>
                    <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Jumlah jam</th>
                    <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataAbsensi as $index => $absensi)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border border-gray-200">{{ $index + 1 }}</td>
                    <td class="p-3 border border-gray-200">{{ $absensi['nama'] }}</td>
                    <td class="p-3 border border-gray-200">{{ $absensi['tanggal'] }}</td>
                    <td class="p-3 border border-gray-200">{{ $absensi['jam_masuk'] }} - {{ $absensi['jam_pulang'] }}</td>
                    <td class="p-3 border border-gray-200">{{ $absensi['jml_jam_kerja'] }} Jam</td>

                    <td class="p-3 border border-gray-200">
                        @php
                        $status = $absensi['status_kehadiran'];
                        $bgColor = 'bg-gray-100 text-gray-800'; // Default abu-abu

                        // Logika warna Pill berdasarkan status
                        if ($status == 'Hadir') {
                        $bgColor = 'bg-green-100 text-green-800';
                        } elseif ($status == 'Setengah Hari') {
                        $bgColor = 'bg-yellow-100 text-yellow-800';
                        } elseif ($status == 'Alpa' || str_contains($status, 'Tidak Valid') || str_contains($status, 'Tidak Sah')) {
                        $bgColor = 'bg-red-100 text-red-800';
                        }
                        @endphp
                        <span class="px-3 py-1 text-xs font-semibold rounded-full inline-block {{ $bgColor }}">
                            {{ $status }}
                        </span>
                    </td>


                    <input type="hidden" name="data[{{ $index }}][guru_id]" value="{{ $absensi['guru_id'] }}">
                    <input type="hidden" name="data[{{ $index }}][tanggal]" value="{{ $absensi['tanggal'] }}">
                    <input type="hidden" name="data[{{ $index }}][jam_masuk]" value="{{ $absensi['jam_masuk'] }}">
                    <input type="hidden" name="data[{{ $index }}][jam_pulang]" value="{{ $absensi['jam_pulang'] }}">
                    <input type="hidden" name="data[{{ $index }}][jml_jam_kerja]" value="{{ $absensi['jml_jam_kerja'] }}">
                    <input type="hidden" name="data[{{ $index }}][status_kehadiran]" value="{{ $absensi['status_kehadiran'] }}">
                    <input type="hidden" name="data[{{ $index }}][potongan_jam]" value="{{ $absensi['potongan_jam'] }}">

                    @if(request('tanggal_libur'))
                    @foreach(request('tanggal_libur') as $libur)
                    @if($libur) <input type="hidden" name="tanggal_libur[]" value="{{ $libur }}">
                    @endif
                    @endforeach
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded mt-4">Simpan ke Database</button>
    </form>
    @endisset
</div>

<script>
    function tambahLibur() {
        const container = document.getElementById('container-libur');
        const inputBaru = document.createElement('input');
        inputBaru.type = 'date';
        inputBaru.name = 'tanggal_libur[]';
        inputBaru.className = 'w-full text-slate-700 font-medium text-sm bg-white border border-gray-300 py-2 px-3 rounded focus:outline-none focus:border-teal-500';
        container.appendChild(inputBaru);
    }
</script>
@endsection
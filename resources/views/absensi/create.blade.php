@extends('admin.layout')
@section('content')
    <div class="w-full bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-teal-600 p-3">
        <h2 class="text-xl font-semibold text-white">Import Data Absen</h2>
    </div>

    <!-- Form untuk Upload File Excel -->
    <form method="POST" action="{{ route('absensi.preview') }}" enctype="multipart/form-data" class="p-6">
        @csrf
        <div class="max-w-md mx-auto">
            <label class="text-base text-slate-900 font-medium mb-3 block">Upload file Absensi</label>
            <input type="file"
                name="file"
                class="w-full text-slate-500 font-medium text-sm bg-white border file:cursor-pointer cursor-pointer file:border-0 file:py-3 file:px-4 file:mr-4 file:bg-gray-100 file:hover:bg-gray-200 file:text-slate-500 rounded"
                accept=".xls,.xlsx" />
            <p class="text-xs text-slate-500 mt-2">Hanya File Excel (XLS, XLSX) yang Diupload.</p>
        </div>
        <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded mt-4">Preview</button>
        <a href="{{ route(name: 'absensi.create') }}">
            <button type="button" class="bg-red-600 text-white px-6 py-2 rounded mt-4">Reset</button>
        </a>
    </form>

    @if(session('success'))
        <div class="bg-teal-200 text-teal-800 p-3 rounded mt-4">
            {{ session('success') }}
        </div>
    @endif

    @isset($dataAbsensi)
    <!-- Tabel Preview Data Absensi -->
    <form method="POST" action="{{ route('absensi.store') }}">
        @csrf
        <table class="w-full border-collapse mt-6">
            <thead>
                <tr class="bg-teal-50">
                    <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">No</th>
                    <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Nama</th>
                    <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Departemen</th>
                    <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Tanggal</th>
                    <th class="p-3 text-left text-teal-800 font-semibold border-b border-teal-200">Absen</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataAbsensi as $index => $absensi)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border border-gray-200">{{ $index + 1 }}</td>
                    <td class="p-3 border border-gray-200">{{ $absensi['nama'] }}</td> <!-- Nama -->
                    <td class="p-3 border border-gray-200">{{ $absensi['departemen'] }}</td> <!-- departemen -->
                    <td class="p-3 border border-gray-200">{{ $absensi['tanggal'] }}</td> <!-- tanggal -->
                    <td class="p-3 border border-gray-200">{{ $absensi['total'] }}</td> <!-- total -->
                    <input type="hidden" name="data[{{ $index }}][nama]" value="{{ $absensi['nama'] }}">
                    <input type="hidden" name="data[{{ $index }}][departemen]" value="{{ $absensi['departemen'] }}">
                    <input type="hidden" name="data[{{ $index }}][tanggal]" value="{{ $absensi['tanggal'] }}">
                    <input type="hidden" name="data[{{ $index }}][menit]" value="{{ $absensi['total'] }}">
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded mt-4">Simpan ke Database</button>
    </form>
    @endisset
</div>
@endsection


@push('scripts')
<script>

</script>
@endpush

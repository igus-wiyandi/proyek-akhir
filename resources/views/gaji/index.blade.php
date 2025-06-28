
@extends($isAdmin ? 'admin.layout' : 'tampil_guru.layout')
@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
@endpush
@section('content')
    <div class="w-full p-6 bg-gray-100 ">
        <div class="w-full bg-white rounded-lg shadow-md overflow-hidden">

            @if (session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                    class="flex justify-center mt-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-teal-600 p-4 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Perhitungan Gaji</h2>
                <form method="POST" action="{{ route('perhitungan_gaji.range') }}">
                    @csrf
                    <div id="date-range-picker" date-rangepicker class="flex items-center">
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input id="datepicker-range-start" name="start" type="text" value="{{ $startDate ?? '' }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Select date start" required>
                        </div>
                        <span class="mx-4 text-gray-500">to</span>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input id="datepicker-range-end" name="end" type="text" value="{{ $endDate ?? '' }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Select date end" required>
                        </div>
                    </div>
                    <button type="submit"
                        class="ml-4 bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 transition-colors">
                        Submit
                    </button>
                </form>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari
                            Kerja</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Hadir
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Tidak
                            Hadir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gaji
                            Mengajar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Honor
                            Tambahan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                            Gaji</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if (isset($dataGaji) && count($dataGaji) !== 0)
                        @foreach ($dataGaji as $gaji)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $gaji['guru'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $gaji['jabatan'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $gaji['total_hari'] }} hari</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $gaji['jam_hadir'] }} jam</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $gaji['jam_tidak_hadir'] }} jam</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp
                                    {{ number_format($gaji['gaji_mengajar'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp
                                    {{ number_format($gaji['honor_tambahan'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">Rp
                                    {{ number_format($gaji['gaji_total'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <!-- Breakdown perhitungan -->
            @if (isset($dataGaji) && count($dataGaji) !== 0)
                @foreach ($dataGaji as $gaji)
                    <div class="mt-8 p-4 border rounded-lg">
                        <h3 class="font-bold">Rincian Perhitungan untuk {{ $gaji['guru'] }}</h3>
                        <p>Periode: {{ $gaji['periode'] }}</p>
                        <ul class="list-disc pl-5 mt-2">
                            <li>Total hari kerja: {{ $gaji['total_hari'] }} hari × 8 jam = {{ $gaji['total_hari'] * 8 }}
                                jam</li>
                            <li>Total hadir: {{ $gaji['jam_hadir'] }} jam</li>
                            <li>Tidak hadir: {{ $gaji['jam_tidak_hadir'] }} jam</li>
                            <li>Gaji mengajar: {{ $gaji['jam_hadir'] }} jam × Rp
                                {{ number_format($gaji['tarif_per_jam'], 0, ',', '.') }} = Rp
                                {{ number_format($gaji['gaji_mengajar'], 0, ',', '.') }}</li>
                            <li>Honor wali kelas: Rp {{ number_format($gaji['honor_tambahan'], 0, ',', '.') }}</li>
                            <li class="font-bold">Total gaji: Rp {{ number_format($gaji['gaji_total'], 0, ',', '.') }}</li>
                        </ul>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection

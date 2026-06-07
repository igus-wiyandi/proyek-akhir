@extends('admin.layout')
@push('styles')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

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
                    <input type="date" name="tanggal_mulai" required value="{{ old('tanggal_mulai', request('tanggal_mulai')) }}"
                        class="w-full text-slate-700 font-medium text-sm bg-white border border-gray-300 py-2 px-3 rounded focus:outline-none focus:border-teal-500" />
                </div>
                <div class="w-1/3">
                    <label class="text-base text-slate-900 font-medium mb-2 block">Tanggal Akhir <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_akhir" required value="{{ old('tanggal_akhir', request('tanggal_akhir')) }}"
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
            <input type="file" name="file" class="w-full text-slate-500 font-medium text-sm bg-white border file:cursor-pointer cursor-pointer file:border-0 file:py-3 file:px-4 file:mr-4 file:bg-gray-100 file:hover:bg-gray-200 file:text-slate-500 rounded" accept=".xls,.xlsx" />
            <p class="text-xs text-slate-500 mt-2">Hanya File Excel (XLS, XLSX) yang Diupload.</p>
        </div>

        <div class="max-w-md mx-auto flex gap-2">
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded mt-4">Preview</button>
            <a href="{{ route('absensi.create') }}" class="bg-red-600 text-white px-6 py-2 rounded mt-4">Reset</a>
        </div>
    </form>

    @if ($errors->any())
    <div class="bg-red-200 text-red-800 p-3 rounded mt-4 mb-4 mx-6">
        <strong>Peringatan!</strong>
        <ul class="list-disc list-inside mt-2">
            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
    @endif

    @if(session('success')) <div class="bg-teal-200 text-teal-800 p-3 rounded m-4 mx-6">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="bg-red-200 text-red-800 p-3 rounded m-4 mx-6"><strong>Terjadi Kesalahan:</strong> {{ session('error') }}</div> @endif

    @isset($dataAbsensi)

    <div x-data="previewTable({{ json_encode($dataAbsensi) }}, {{ json_encode($listGuru ?? []) }})" class="p-6 pt-0 border-t mt-4">

        <div class="flex justify-between items-center mt-4">
            <h3 class="font-bold text-gray-800">Preview Data (Bisa Diedit sebelum disimpan)</h3>
            <button type="button" @click="addRow()" class="bg-blue-600 text-white px-4 py-2 text-sm rounded shadow hover:bg-blue-700">
                + Tambah Baris Manual
            </button>
        </div>

        <form method="POST" action="{{ route('absensi.store') }}">
            @csrf
            <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
            <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">

            <table class="w-full border-collapse mt-4 text-sm">
                <thead>
                    <tr class="bg-teal-50">
                        <th class="p-2 border text-left text-teal-800">No</th>
                        <th class="p-2 border text-left text-teal-800">Nama Guru</th>
                        <th class="p-2 border text-left text-teal-800">Tanggal</th>
                        <th class="p-2 border text-left text-teal-800 w-32">Scan Masuk</th>
                        <th class="p-2 border text-left text-teal-800 w-32">Scan Pulang</th>
                        <th class="p-2 border text-left text-teal-800 w-32">Status Kehadiran</th>
                        <th class="p-2 border text-center text-teal-800 w-24">Potong (Jam)</th>
                        <th class="p-2 border text-center text-teal-800 w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, index) in rows" :key="index">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-2 border text-center" x-text="index + 1"></td>

                            <template x-if="!row.is_editing">
                                <td class="p-2 border font-medium text-gray-800" x-text="row.nama"></td>
                            </template>
                            <template x-if="!row.is_editing">
                                <td class="p-2 border text-gray-600" x-text="row.tanggal"></td>
                            </template>
                            <template x-if="!row.is_editing">
                                <td class="p-2 border text-center" x-text="row.jam_masuk || '-'"></td>
                            </template>
                            <template x-if="!row.is_editing">
                                <td class="p-2 border text-center" x-text="row.jam_pulang || '-'"></td>
                            </template>
                            <template x-if="!row.is_editing">
                                <td class="p-2 border text-center font-semibold" x-text="row.status_kehadiran"></td>
                            </template>
                            <template x-if="!row.is_editing">
                                <td class="p-2 border text-center font-bold text-red-600" x-text="row.potongan_jam"></td>
                            </template>

                            <template x-if="row.is_editing">
                                <td class="p-1 border">
                                    <select x-model="row.guru_id" @change="updateNama(row)" class="w-full p-1 border rounded text-xs">
                                        <option value="">-- Pilih Guru --</option>
                                        <template x-for="guru in gurus" :key="guru.id">
                                            <option :value="guru.id" x-text="guru.nama" :selected="guru.id == row.guru_id"></option>
                                        </template>
                                    </select>
                                </td>
                            </template>
                            <template x-if="row.is_editing">
                                <td class="p-1 border"><input type="date" x-model="row.tanggal" class="w-full p-1 border rounded text-xs"></td>
                            </template>
                            <template x-if="row.is_editing">
                                <td class="p-1 border"><input type="time" x-model="row.jam_masuk" class="w-full p-1 border rounded text-xs"></td>
                            </template>
                            <template x-if="row.is_editing">
                                <td class="p-1 border"><input type="time" x-model="row.jam_pulang" class="w-full p-1 border rounded text-xs"></td>
                            </template>
                            <template x-if="row.is_editing">
                                <td class="p-1 border">
                                    <select x-model="row.status_kehadiran" class="w-full p-1 border rounded text-xs">
                                        <option value="Hadir">Hadir</option>
                                        <option value="Setengah Hari">Setengah Hari</option>
                                        <option value="Alpa">Alpa</option>
                                    </select>
                                </td>
                            </template>
                            <template x-if="row.is_editing">
                                <td class="p-1 border"><input type="number" x-model="row.potongan_jam" class="w-full p-1 border rounded text-xs text-center"></td>
                            </template>

                            <td class="p-2 border text-center space-x-1">
                                <template x-if="!row.is_editing">
                                    <button type="button" @click="row.is_editing = true" cclass="text-teal-600 hover:text-teal-800 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg></button>
                                </template>
                                <template x-if="row.is_editing">
                                    <button type="button" @click="row.is_editing = false" class="text-green-600 hover:bg-green-100 px-2 py-1 rounded text-xs font-bold">✔ OK</button>
                                </template>
                                <button type="button" @click="removeRow(index)" class="text-red-600 hover:text-red-800 transition-colors" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0h4m-7 4h10"></path>
                                    </svg></button>
                            </td>

                            <input type="hidden" :name="'data['+index+'][guru_id]'" :value="row.guru_id">
                            <input type="hidden" :name="'data['+index+'][tanggal]'" :value="row.tanggal">
                            <input type="hidden" :name="'data['+index+'][jam_masuk]'" :value="row.jam_masuk">
                            <input type="hidden" :name="'data['+index+'][jam_pulang]'" :value="row.jam_pulang">
                            <input type="hidden" :name="'data['+index+'][status_kehadiran]'" :value="row.status_kehadiran">
                            <input type="hidden" :name="'data['+index+'][potongan_jam]'" :value="row.potongan_jam">
                            <input type="hidden" :name="'data['+index+'][jml_jam_kerja]'" value="0">
                        </tr>
                    </template>

                    <tr x-show="rows.length === 0">
                        <td colspan="8" class="p-6 text-center text-gray-500 bg-gray-50">Semua baris telah dihapus. Klik "Tambah Baris Manual" untuk mengisi data.</td>
                    </tr>
                </tbody>
            </table>

            @if(request('tanggal_libur'))
            @foreach(request('tanggal_libur') as $libur)
            @if($libur) <input type="hidden" name="tanggal_libur[]" value="{{ $libur }}"> @endif
            @endforeach
            @endif

            <button type="submit" class="bg-teal-600 text-white px-8 py-3 rounded-lg font-bold mt-6 shadow hover:bg-teal-700 w-full md:w-auto">
                Simpan ke Database Terverifikasi
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('previewTable', (initialData, gurus) => ({
                // Tambahkan state 'is_editing' false pada setiap baris bawaan excel
                rows: initialData.map(item => ({
                    ...item,
                    is_editing: false
                })),
                gurus: gurus,

                // Fungsi Hapus Baris
                removeRow(index) {
                    if (confirm('Yakin ingin menghapus baris absen ini dari antrean simpan?')) {
                        this.rows.splice(index, 1);
                    }
                },

                // Fungsi Tambah Baris Kosong Baru
                addRow() {
                    this.rows.push({
                        guru_id: '',
                        nama: '',
                        tanggal: document.querySelector('input[name=tanggal_mulai]').value || '',
                        jam_masuk: '07:10',
                        jam_pulang: '16:05',
                        status_kehadiran: 'Hadir',
                        potongan_jam: 0,
                        is_editing: true // Langsung buka mode edit
                    });
                },

                // Fungsi Update Nama saat Dropdown Guru diganti
                updateNama(row) {
                    let guru = this.gurus.find(g => g.id == row.guru_id);
                    if (guru) {
                        row.nama = guru.nama;
                    } else {
                        row.nama = '';
                    }
                }
            }));
        });

        // Script untuk form tanggal libur
        function tambahLibur() {
            const container = document.getElementById('container-libur');
            const inputBaru = document.createElement('input');
            inputBaru.type = 'date';
            inputBaru.name = 'tanggal_libur[]';
            inputBaru.className = 'w-full text-slate-700 font-medium text-sm bg-white border border-gray-300 py-2 px-3 rounded focus:outline-none focus:border-teal-500 mt-2';
            container.appendChild(inputBaru);
        }
    </script>
    @endisset
</div>
@endsection
<table>
    <thead>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 14px;">LAPORAN RIWAYAT PENGGAJIAN GURU</th>
        </tr>
        @if($start && $end)
        <tr>
            <th colspan="7" style="text-align: center;">Periode: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</th>
        </tr>
        @endif
        <tr>
            <th colspan="7"></th>
        </tr>
        <tr>
            <th style="font-weight: bold; text-align: center;">No</th>
            <th style="font-weight: bold;">Nama Periode</th>
            <th style="font-weight: bold;">Nama Guru</th>
            <th style="font-weight: bold; text-align: center;">Jam Bersih</th>
            <th style="font-weight: bold; text-align: center;">Potongan</th>
            <th style="font-weight: bold; text-align: right;">Tunjangan Tugas</th>
            <th style="font-weight: bold; text-align: right;">Total Gaji</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dataGaji as $index => $gaji)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td>{{ $gaji->nama_periode }}</td>
            <td>{{ $gaji->guru->nama ?? 'Data Guru Dihapus' }}</td>
            <td style="text-align: center;">{{ $gaji->total_jam_bersih }} Jam</td>
            <td style="text-align: center;">{{ $gaji->total_potongan_jam }} Jam</td>
            <td style="text-align: right;">Rp {{ number_format($gaji->tunjangan_tambahan, 0, ',', '.') }}</td>
            <td style="text-align: right; font-weight: bold;">Rp {{ number_format($gaji->total_gaji_bersih, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
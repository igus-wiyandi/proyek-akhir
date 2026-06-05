<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14px;">
                LAPORAN REKAPITULASI ABSENSI GURU
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </th>
        </tr>
        <tr>
            <th colspan="6"></th>
        </tr>

        <tr>
            <th style="font-weight: bold; text-align: center;">No</th>
            <th style="font-weight: bold; text-align: left;">Nama Guru</th>
            <th style="font-weight: bold; text-align: center;">Tanggal</th>
            <th style="font-weight: bold; text-align: center;">Jam Scan</th>
            <th style="font-weight: bold; text-align: center;">Status</th>
            <th style="font-weight: bold; text-align: center;">Potongan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reports as $index => $report)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td style="text-align: left;">{{ $report->guru->nama ?? 'Data Guru Dihapus' }}</td>
            <td style="text-align: center;">{{ \Carbon\Carbon::parse($report->tanggal)->format('d M Y') }}</td>
            <td style="text-align: center;">{{ $report->jam_masuk ?? '-' }} s/d {{ $report->jam_pulang ?? '-' }}</td>
            <td style="text-align: center;">{{ $report->status_kehadiran }}</td>
            <td style="text-align: center;">{{ $report->potongan_jam }} Jam</td>
        </tr>
        @endforeach
    </tbody>
</table>
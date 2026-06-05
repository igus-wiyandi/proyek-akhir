<!DOCTYPE html>
<html>

<head>
    <title>Laporan Absensi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .font-bold {
            font-weight: bold;
        }

        .mb-6 {
            margin-bottom: 24px;
        }

        .mt-12 {
            margin-top: 48px;
        }

        table {
            w-full border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-red {
            color: #dc2626;
        }

        .text-green {
            color: #16a34a;
        }

        .w-64 {
            width: 250px;
            display: inline-block;
        }
    </style>
</head>

<body>

    <div class="text-center mb-6" style="border-b: 2px solid #000; padding-bottom: 10px;">
        <h1 style="margin: 0; font-size: 18px;" class="uppercase">Laporan Rekapitulasi Absensi Guru</h1>
        <p style="margin: 5px 0 0 0;">Periode Tanggal: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 35%;">Nama Guru</th>
                <th style="width: 15%; text-align: center;">Tanggal</th>
                <th style="width: 20%; text-align: center;">Jam Scan</th>
                <th style="width: 15%; text-align: center;">Status</th>
                <th style="width: 10%; text-align: center;">Potongan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $index => $report)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $report->guru->nama ?? 'Data Guru Dihapus' }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($report->tanggal)->format('d M Y') }}</td>
                <td class="text-center">{{ $report->jam_masuk ?? '-' }} s/d {{ $report->jam_pulang ?? '-' }}</td>
                <td class="text-center">{{ $report->status_kehadiran }}</td>
                <td class="text-center font-bold {{ $report->potongan_jam > 0 ? 'text-red' : '' }}">{{ $report->potongan_jam }} Jam</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-12" style="text-align: right;">
        <div class="text-center w-64">
            <p>Bangka, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <p class="font-bold" style="margin-top: 5px; margin-bottom: 60px;">Kepala Madrasah,</p>
            <p>_______________________</p>
        </div>
    </div>

</body>
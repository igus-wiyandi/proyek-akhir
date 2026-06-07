<!DOCTYPE html>
<html>

<head>
    <title>Riwayat Penggajian Guru</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .mb-6 {
            margin-bottom: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #bcbcbc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-green {
            color: #16a34a;
        }

        .text-red {
            color: #dc2626;
        }

        .w-64 {
            width: 250px;
            display: inline-block;
        }
    </style>
</head>

<body>

    <div class="text-center mb-6" style="border-b: 2px solid #000; padding-bottom: 10px;">
        <h1 style="margin: 0; font-size: 16px; text-transform: uppercase;">Laporan Riwayat Penggajian Guru</h1>
        @if($start && $end)
        <p style="margin: 5px 0 0 0;">Periode Tanggal: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 20%;">Periode / Nama Keterangan</th>
                <th style="width: 25%;">Nama Guru</th>
                <th style="width: 12%; text-align: center;">Jam Bersih</th>
                <th style="width: 12%; text-align: center;">Potongan</th>
                <th style="width: 13%; text-align: right;">Tunjangan Tugas</th>
                <th style="width: 13%; text-align: right;">Total Gaji Bersih</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataGaji as $index => $gaji)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $gaji->nama_periode }}</td>
                <td class="font-bold">{{ $gaji->guru->nama ?? 'Data Guru Dihapus' }}</td>
                <td class="text-center text-green font-bold">{{ $gaji->total_jam_bersih }} Jam</td>
                <td class="text-center {{ $gaji->total_potongan_jam > 0 ? 'text-red font-bold' : '' }}">{{ $gaji->total_potongan_jam }} Jam</td>
                <td class="text-right">Rp {{ number_format($gaji->tunjangan_tambahan, 0, ',', '.') }}</td>
                <td class="text-right font-bold" style="background-color: #f9f9f9;">Rp {{ number_format($gaji->total_gaji_bersih, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-12" style="text-align: right; margin-top: 40px;">
        <div class="text-center w-64">
            <p>Bangka, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <p class="font-bold" style="margin-top: 5px; margin-bottom: 50px;">Kepala Madrasah,</p>
            <p>_______________________</p>
        </div>
    </div>

</body>

</html>
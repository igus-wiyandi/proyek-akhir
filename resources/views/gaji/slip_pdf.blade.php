<!DOCTYPE html>
<html>

<head>
    <title>Slip Gaji - {{ $gaji->guru->nama }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            margin: 0;
        }

        .subtitle {
            font-size: 11px;
            color: #666;
            margin: 5px 0 0 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .info-table td {
            padding: 3px 0;
        }

        .section-title {
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 10px;
            color: #111;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .details-table td {
            padding: 5px 0;
        }

        .details-table .amount {
            text-align: right;
        }

        .total-row {
            border-top: 1px solid #eee;
            font-weight: bold;
            padding-top: 8px;
            margin-top: 5px;
        }

        .divider {
            border-bottom: 1px dashed #ccc;
            margin: 15px 0;
        }

        .grand-total {
            background-color: #eff6ff;
            padding: 15px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            color: #1d4ed8;
            text-transform: uppercase;
        }

        .grand-total table {
            width: 100%;
        }

        .grand-total td.amount {
            text-align: right;
            font-size: 16px;
        }
    </style>
</head>

<body>

    @php
    // Kalkulasi Rupiah untuk UI
    $pendapatanPokok = ($gaji->total_jam_bersih + $gaji->total_potongan_jam) * $gaji->tarif_per_jam;
    $totalPendapatanKotor = $pendapatanPokok + $gaji->tunjangan_tambahan;
    $potonganAbsen = $gaji->total_potongan_jam * $gaji->tarif_per_jam;

    // Tarik nama jabatan dari tabel kategori melalui relasi tabel jabatan
    $daftarJabatan = \App\Models\Jabatan::join('kategori', 'jabatan.kategori_id', '=', 'kategori.id')
    ->where('jabatan.guru_id', $gaji->guru_id)
    ->pluck('kategori.nama')
    ->toArray();

    // Jika kosong (tidak ada tugas tambahan), tampilkan Tenaga Pendidik.
    // Jika ada, gabungkan namanya (contoh: "Guru / Pembina UKS, Pustakawan")
    $teksJabatan = empty($daftarJabatan) ? 'Tenaga Pendidik' : implode(', ', $daftarJabatan);
    @endphp

    <div class="container">
        <div class="header">
            <p class="title">Pondok pesantren Nurul Ihsan</p>
            <p class="subtitle">SLIP GAJI <br> {{ $gaji->nama_periode }}</p>
        </div>

        <table class="info-table">
            <tr>
                <td width="20%">Nama</td>
                <td width="80%">: {{ $gaji->guru->nama }}</td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>: {{ $gaji->guru->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td class="py-1 font-semibold text-gray-800">: {{ $teksJabatan }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="section-title">PENDAPATAN</div>
        <table class="details-table">
            <tr>
                <td>Gaji Pokok ({{ $gaji->total_jam_bersih }} Jam)</td>
                <td class="amount">Rp {{ number_format($pendapatanPokok, 0, ',', '.') }}</td>
            </tr>
            @if($gaji->tunjangan_tambahan > 0)
            <tr>
                <td>Tunjangan Tugas Tambahan</td>
                <td class="amount">Rp {{ number_format($gaji->tunjangan_tambahan, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="2">
                    <div class="total-row"></div>
                </td>
            </tr>
            <tr style="font-weight: bold;">
                <td>Total Pendapatan</td>
                <td class="amount">Rp {{ number_format($totalPendapatanKotor, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="section-title">POTONGAN</div>
        <table class="details-table">
            <tr>
                <td>Absensi Kehadiran ({{ $gaji->total_potongan_jam }} Jam)</td>
                <td class="amount">Rp {{ number_format($potonganAbsen, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="total-row"></div>
                </td>
            </tr>
            <tr style="font-weight: bold;">
                <td>Total Potongan</td>
                <td class="amount">Rp {{ number_format($potonganAbsen, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider" style="border-bottom: 2px solid #eff6ff;"></div>

        <div class="grand-total">
            <table>
                <tr>
                    <td>TOTAL DITERIMA</td>
                    <td class="amount">Rp {{ number_format($gaji->total_gaji_bersih, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>
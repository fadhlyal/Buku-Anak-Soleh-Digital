<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Muhasabah Ibadah Harian - {{ $student->user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        table {
            font-size: 10px;
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .tanggal {
            text-align: right;
        }
        hr {
            border: 1px solid #000;
            margin: 10px 0;
        }
        .red-x {
            text-align: center;
            font-size: 12px;
            color: red;
            font-weight: bold;
        }
        .green-v {
            text-align: center;
            font-size: 12px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Letterhead Section -->
    <div class="letterhead">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 80px; text-align: right; vertical-align: middle; border: none;">
                    <img src="{{ asset('Logo.png') }}" alt="Logo" style="width: 60px;">
                </td>
                <td style="text-align: center; vertical-align: middle; border: none;">
                    <h2 style="margin: 0; font-size: 16px;">SD AR RAFI 1</h2>
                    <p style="margin: 0; font-size: 12px;">Jl. Sekejati 3 No.20, Sukapura, Kec. Kiaracondong, Kota Bandung, Jawa Barat 40285 - Telp. (022) 7311009</p>
                </td>
            </tr>
        </table>

        <!-- Double horizontal line -->
        <hr style="border: 1px solid black; margin-top: 10px;">
        <hr style="border: 2px solid black; margin-top: -5px; margin-bottom: 10px;">
    </div>
    
    <h2 style="text-align: center;">LEMBAR MUHASABAH IBADAH HARIAN</h2>
    
    <hr>

    <p>Nama Siswa : <span>{{ $student->user->name }}</span></p>
    <p>Nama Kelas : <span>{{ $student->class_name }}</span></p>
    <p>Nama Wali Kelas : <span>{{ $student->teacher->user->name }}</span></p>

    <hr>

    <table>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Hari/Tgl</th>
            <th colspan="2" style="text-align: center">Mengaji</th>
            <th rowspan="2">Shalat Sunnah</th>
            <th colspan="5" style="text-align: center">Shalat Fardhu</th>
            <th colspan="2" style="text-align: center">Paraf</th>
        </tr>
        <tr>
            <th>Tilawatil/Al-Qur'an</th>
            <th>Halaman Ayat</th>
            <th>Subuh</th>
            <th>Dzuhur</th>
            <th>Ashar</th>
            <th>Maghrib</th>
            <th>Isya</th>
            <th>Guru</th>
            <th>Orang Tua</th>
        </tr>
        @foreach ($muhasabahReports as $index => $report)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($report->time_stamp)->locale('id')->translatedFormat('l, d-m-Y') }}</td>
            <td>{{ $report->surah_name ? $report->surah_name : '-' }}</td>
            <td>{{ $report->surah_ayat ? $report->surah_ayat : '-'}}</td>
            <td class="{{ $report->sunnah_pray ? 'green-v' : 'red-x' }}">{{ $report->sunnah_pray ? 'V' : 'X' }}</td>
            <td class="{{ $report->subuh_pray ? 'green-v' : 'red-x' }}">{{ $report->subuh_pray ? 'V' : 'X' }}</td>
            <td class="{{ $report->dzuhur_pray ? 'green-v' : 'red-x' }}">{{ $report->dzuhur_pray ? 'V' : 'X' }}</td>
            <td class="{{ $report->ashar_pray ? 'green-v' : 'red-x' }}">{{ $report->ashar_pray ? 'V' : 'X' }}</td>
            <td class="{{ $report->maghrib_pray ? 'green-v' : 'red-x' }}">{{ $report->maghrib_pray ? 'V' : 'X' }}</td>
            <td class="{{ $report->isya_pray ? 'green-v' : 'red-x' }}">{{ $report->isya_pray ? 'V' : 'X' }}</td>
            <td>{{ $report->teacher_sign ? 'Sudah' : 'Belum' }}</td>
            <td>{{ $report->parent_sign ? 'Sudah' : 'Belum' }}</td>
        </tr>
        @endforeach
    </table>

</body>
</html>
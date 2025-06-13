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
    @include('convert.partials.letterhead')
    
    <h2 style="text-align: center;">LEMBAR MUHASABAH IBADAH HARIAN</h2>
    <p style="text-align: center;">Periode: {{ $month }}-{{ $year }}</p>
    
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
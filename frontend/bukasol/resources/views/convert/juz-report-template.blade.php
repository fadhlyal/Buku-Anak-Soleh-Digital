<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Laporan Juz {{ $juzNumber }} - {{ $student->user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
            word-wrap: break-word;
            word-break: break-word;
            vertical-align: top;
        }
        th:first-child, td:first-child {
            width: 6%;
        }
        hr {
            border: 1px solid #000;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    @include('convert.partials.letterhead')
    
    <h2 style="text-align: center;">LEMBAR LAPORAN JUZ {{ $juzNumber }}</h2>
    <p style="text-align: center;">Periode: {{ $month }}-{{ $year }}</p>
    
    <hr>

    <p>Nama Siswa : <span>{{ $student->user->name }}</span></p>
    <p>Nama Kelas : <span>{{ $student->class_name }}</span></p>
    <p>Nama Wali Kelas : <span>{{ $student->teacher->user->name }}</span></p>

    <hr>

    <table >
        <tr>
            <th>No</th>
            <th>Hari/Tanggal</th>
            <th>Surat</th>
            <th>Ayat</th>
            <th>Paraf Guru</th>
        </tr>
        @foreach ($juzReports as $index => $report)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($report->time_stamp)->locale('id')->translatedFormat('l, d-m-Y') }}</td>
            <td>{{ $report->surah_name }}</td>
            <td>{{ $report->surah_ayat }}</td>
            <td>{{ $report->teacher_sign ? 'Sudah' : 'Belum' }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
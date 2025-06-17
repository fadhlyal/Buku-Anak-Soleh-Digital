<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Catatan Harian - {{ $student->user->name }}</title>
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

    <h2 style="text-align: center;">LEMBAR CATATAN HARIAN</h2>
    <p style="text-align: center;">Periode: {{ $month }}-{{ $year }}</p>
    
    <hr>

    <p>Nama Siswa : <span>{{ $student->user->name }}</span></p>
    <p>Nama Kelas : <span>{{ $student->class_name }}</span></p>
    <p>Nama Wali Kelas : <span>{{ $student->teacher->user->name }}</span></p>

    <hr>

    <h3>Aktivitas Harian</h3>
    <table >
        <tr>
            <th>No</th>
            <th>Hari/Tanggal</th>
            <th>Aktivitas</th>
            <th>Rincian Aktivitas</th>
            <th>Pertanyaan Orang Tua</th>
            <th>Jawaban Guru</th>
            <th>Paraf Guru</th>
        </tr>
        @foreach ($dailyActivities as $index => $activity)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ \Carbon\Carbon::parse($activity->time_stamp)->locale('id')->translatedFormat('l, d-m-Y') }}</td>
            <td>{{ $activity->activity }}</td>
            <td>{{ $activity->activity_detail }}</td>
            <td>{{ $activity->parent_question ? $activity->parent_question : '-' }}</td>
            <td>{{ $activity->teacher_answer ? $activity->teacher_answer : '-'}}</td>
            <td>{{ $activity->teacher_sign ? 'Sudah' : 'Belum' }}</td>
        </tr>
        @endforeach
    </table>

    <h3>Ekstrakurikuler</h3>
    <table >
        <tr>
            <th>No</th>
            <th>Hari/Tanggal</th>
            <th>Nama Ekskul</th>
            <th>Rincian Ekskul</th>
            <th>Pertanyaan Orang Tua</th>
            <th>Jawaban Guru</th>
            <th>Paraf Guru</th>
        </tr>
        @foreach ($extracurriculars as $index => $activity)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ \Carbon\Carbon::parse($activity->time_stamp)->locale('id')->translatedFormat('l, d-m-Y') }}</td>
            <td>{{ $activity->activity }}</td>
            <td>{{ $activity->activity_detail }}</td>
            <td>{{ $activity->parent_question ? $activity->parent_question : '-' }}</td>
            <td>{{ $activity->teacher_answer ? $activity->teacher_answer : '-'}}</td>
            <td>{{ $activity->teacher_sign ? 'Sudah' : 'Belum' }}</td>
        </tr>
        @endforeach
    </table>

    <h3>Prestasi</h3>
    <table >
        <tr>
            <th>No</th>
            <th>Hari/Tanggal</th>
            <th>Nama Perlombaan</th>
            <th>Rincian Perlombaan</th>
            <th>Pertanyaan Orang Tua</th>
            <th>Jawaban Guru</th>
            <th>Paraf Guru</th>
        </tr>
        @foreach ($achievements as $index => $activity)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ \Carbon\Carbon::parse($activity->time_stamp)->locale('id')->translatedFormat('l, d-m-Y') }}</td>
            <td>{{ $activity->activity }}</td>
            <td>{{ $activity->activity_detail }}</td>
            <td>{{ $activity->parent_question ? $activity->parent_question : '-' }}</td>
            <td>{{ $activity->teacher_answer ? $activity->teacher_answer : '-'}}</td>
            <td>{{ $activity->teacher_sign ? 'Sudah' : 'Belum' }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
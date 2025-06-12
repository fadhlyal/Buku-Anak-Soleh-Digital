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
    
    <h2 style="text-align: center;">LEMBAR LAPORAN JUZ {{ $juzNumber }}</h2>
    
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
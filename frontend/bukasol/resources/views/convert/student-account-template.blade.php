<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Siswa</title>
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
        }
        .tanggal {
            text-align: right;
        }
        hr {
            border: 1px solid #000;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    @include('convert.partials.letterhead')
    
    <h2 style="text-align: center;">AKUN SISWA</h2>
    
    <hr>

    <table>
        <tr>
            <th>No</th>
            <th>NISN</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Username</th>
            <th>Password</th>
            <th>Kode Orang Tua</th>
        </tr>
        @foreach ($students as $index => $student)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $student->nisn }}</td>
            <td>{{ $student->user->name }}</td>
            <td>{{ $student->class_name }}</td>
            <td>{{ $student->user->username }}</td>
            <td>{{ $student->new_password }}</td>
            <td>{{ $student->parent_code }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
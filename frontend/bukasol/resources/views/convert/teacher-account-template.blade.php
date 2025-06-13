<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Guru</title>
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
    
    <h2 style="text-align: center;">AKUN GURU</h2>
    
    <hr>

    <table>
        <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Username</th>
            <th>Password</th>
        </tr>
        @foreach ($teachers as $index => $teachers)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $teachers->nip }}</td>
            <td>{{ $teachers->user->name }}</td>
            <td>{{ $teachers->class_name }}</td>
            <td>{{ $teachers->user->username }}</td>
            <td>{{ $teachers->new_password }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
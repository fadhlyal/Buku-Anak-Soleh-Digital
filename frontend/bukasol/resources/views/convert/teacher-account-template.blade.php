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
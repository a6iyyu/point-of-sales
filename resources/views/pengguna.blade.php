<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Data Pengguna</title>
</head>

<body>
    <h1>Data Pengguna</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Nama</th>
            <th>ID Level Pengguna</th>
        </tr>
        @foreach ($data as $list)
            <tr>
                <td>{{ $list->user_id }}</td>
                <td>{{ $list->username }}</td>
                <td>{{ $list->nama }}</td>
                <td>{{ $list->level_id }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
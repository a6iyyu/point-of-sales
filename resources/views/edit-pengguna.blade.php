<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Edit Pengguna</title>
</head>

<body>
    <h1>Form Edit Data Pengguna</h1>
    <a href="/pengguna">Kembali</a>
    <br><br>
    <form method="post" action="/pengguna/edit/{{ $data->user_id }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukan Username" value="{{ $data->username }}" />
        <br>
        <label>Nama</label>
        <input type="text" name="nama" placeholder="Masukan Nama" value="{{ $data->nama }}" />
        <br>
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukan Password" value="{{ $data->password }}" />
        <br>
        <label>Level ID</label>
        <input type="number" name="level_id" placeholder="Masukan ID Level" value="{{ $data->level_id }}" />
        <br><br>
        <input type="submit" value="Edit">
    </form>
</body>

</html>
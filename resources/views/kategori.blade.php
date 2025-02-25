<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Data Kategori Barang</title>
</head>

<body>
    <h1>Data Kategori Barang</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Kode Kategori</th>
            <th>Nama Kategori</th>
        </tr>
        @foreach ($data as $list)
            <tr>
                <td>{{ $list->kategori_id }}</td>
                <td>{{ $list->kategori_kode }}</td>
                <td>{{ $list->kategori_nama }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
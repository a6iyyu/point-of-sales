@extends('layouts.template')

@section('content')
    <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="foto_profil">Unggah Foto Profil</label>
            <input type="file" name="foto_profil" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
@endsection
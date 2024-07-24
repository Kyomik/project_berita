@extends('layouts.admin')

@section('content')

<div class="container-admin">
    <form id="createAdmin" action="{{ url('admin/add/index') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Nama Admin</label>
            <input type="text" class="form-control" id="judulInput" name="nama_user" placeholder="Judul...">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="text" class="form-control" id="judulInput" name="username" placeholder="Email...">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="text" class="form-control" id="judulInput" name="password" placeholder="Password...">
        </div>
        <div class="form-group">
            <label style="display: block;">Hak Akses</label>
            <select class="form-select" aria-label="Default select example" name="hak_akses">
                <option selected >pili aksses</option>
                <option value="user_admin">Super Admin</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="features">
            <button type="submit" class="btn btn-primary col-sm-1">Kirim</button>
            <button type="button" class="btn btn-warning col-sm-1" style="margin-left: 5px" onclick="history.back()">Go Back</button>
        </div>
    </form>
</div>
@endsection

 
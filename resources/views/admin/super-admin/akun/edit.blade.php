<!-- resources/views/admin/super-admin/akun/edit.blade.php -->

@extends('layouts.admin')

@section('content')
<div class="container-admin">
    <form action="{{ route('admin/update', ['id' => $admin->id_user]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Nama Admin</label>
            <input type="text" class="form-control" name="nama_user" value="{{ $admin->nama_user }}" placeholder="Nama Admin...">
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username" value="{{ $admin->login->username }}" placeholder="Username...">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="text" id="passwordInput" class="form-control" name="password" value="{{ $admin->login->password }}" placeholder="Password...">
            <input type="hidden" id="passwordHash" name="password_hash" value="">
            {{-- <small><a href="#" id="showPassword">Show Password</a></small> --}}
        </div>
        <div class="form-group">
            <label style="display: block;">Hak Akses</label>
            <select class="form-select" name="hak_akses">
                <option value="admin" {{ $admin->hak_akses == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user_admin" {{ $admin->hak_akses == 'user_admin' ? 'selected' : '' }}>User Admin</option>
            </select>
        </div>
        <div class="features">
            <button type="submit" class="btn btn-primary col-sm-1">Simpan</button>
            <button type="button" class="btn btn-warning col-sm-1" style="margin-left: 5px" onclick="history.back()">Go Back</button>
        </div>
    </form>
</div>

<script>
    try{
        document.getElementById('showPassword').addEventListener('click', function(event) {
        event.preventDefault();
        var passwordInput = document.getElementById('passwordInput');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            this.textContent = 'Hide Password';
        } else {
            passwordInput.type = 'password';
            this.textContent = 'Show Password';
        }
    });
    }catch(err){}
</script>

@endsection

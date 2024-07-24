@extends('layouts.admin')

@section('content')

@php $user = session('user') @endphp

<h4>
    @php
        $status = request()->query('status');
    @endphp
    @if($status)
        <script>alert("Admin Berhasil dibuat")</script>    
    @endif
</h4>
<div class="container-admin">
    <nav class="navbar navbar-light bg-light justify-content-between">
        <a class="navbar-brand">Admin</a>
        <a href="{{ route('admin/tambah', ['token' => $user['secret']]) }}"><button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tambah</button></a>
    </nav>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Admin</th>
                    <th scope="col">Jumlah Berita</th>
                    <th scope="col">Email</th>
                    <th>Password</th>
                    <th>Hak Akses</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="features">
                @php $count = 1; @endphp
                @foreach($admins as $data)
                <tr>
                    <th scope="row">{{ $count }}</th>
                    <td>{{ $data['nama_user'] }}</td>
                    <td>{{ $data['jumlah_berita'] }}</td>
                    <td>{{ $data['email'] }}</td>
                    <td>{{ $data['password'] }}</td>
                    <td>{{ $data['hak_akses'] }} </td>
                    <td>
                        <a href="{{ route('admin.edit', ['id' => $data['id_user'], 'token' => $user['secret']]) }}">
                            <button class="btn btn-warning btn-xs">Edit</button>
                        </a>
                        <form action="{{ route('admin.destroy', ['id' => $data['id_user'], 'token' => $user['secret']]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @php $count++ @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
<div style="display: flex; justify-content: center;">
  {{ $admins->links() }}
</div>
@endsection

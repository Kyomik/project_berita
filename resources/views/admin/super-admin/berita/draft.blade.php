@extends('layouts.admin')

@section('content')
@php $user = session('user') @endphp
<style type="text/css">
  .container-admin{
    padding: 20px;
  }
</style>
<div class="container-admin">
    <!-- <nav class="navbar navbar-light bg-light justify-content-between">
        <a class="navbar-brand">Berita</a>
    </nav> -->
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Judul</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Admin</th>
                    <th>Jumlah hari</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="features">
                @php $count = $berita->firstItem(); @endphp
                @foreach($berita as $key => $data)
                <tr>
                    <th scope="row">{{ $count }}</th>
                    <td>{{ $data['judul_berita'] }}</td>
                    <td>{{ $data['nama_kategori'] }}</td>
                    <td>{{ $data['nama_admin'] }}</td>
                    <td>{{ $data['jumlah_hari'] }}</td>
                    <td>{{ $data['keterangan'] }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('admin/berita/show', ['id' => $data['id_berita'], 'token' => $user['secret']]) }}?status=draft">
                            Preview
                        </a>
                        <button class="btn btn-success confirmated" type="button" data-id= {{ $data['id_berita'] }}>
                            Acc
                        </button>
                        <button class="btn btn-danger declined" type="button" data-id = {{  $data['id_berita'] }}>
                            Decline
                        </button>
                        </td>
                </tr>
                @php $count++ @endphp
                @endforeach
            </tbody>
        </table>
        <div style="display: flex; justify-content: center;">
            {{ $berita->links() }}
        </div>
    </div>
</div>
@endsection

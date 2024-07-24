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
                    <th scope="col">Admin</th>
                    <th>Tanggal Upload</th>
                    <th>Tanggal Update</th>
                    <th>Viewer</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="features">
                @php $count = $berita->firstItem(); @endphp
                @foreach($berita as $data)
                <tr>
                    <th scope="row">{{ $count }}</th>
                    <td>{{ $data->judul_berita }}</td>
                    <td>{{ $data->nama_admin }}</td>
                    <td>{{ $data->tanggal_berita }}</td>
                    <td>{{ $data->tanggal_update }}</td>
                    <td>{{ $data->views }}</td>
                    <td>
                        <a href="{{ route('admin/berita/show', ['id' => $data->id_berita, 'token' => $user['secret']]) }}">
                            <button class="btn btn-primary btn-xs">Preview</button>
                        </a>
                        <button class="btn btn-danger deleted" type="button" data-id = {{  $data->id_berita }}>
                            Delete
                        </button>
                    </td>
                </tr>
                @php $count++ @endphp
                @endforeach
            </tbody>
        </table>
        <style type="text/css">
          .hidden{
            display: none
          }
        </style>
        <div style="display: flex; justify-content: center;">
            {{ $berita->links() }}
        </div>
    </div>
</div>
@endsection

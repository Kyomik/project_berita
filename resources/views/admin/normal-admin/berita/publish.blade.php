@extends('layouts.admin')
@php $user = session('user') @endphp
@section('content')
<h4>
    @php
        $status = request()->query('status');
    @endphp
      @if($status)
        <script>alert("Tunggu hingga admin meng-acc nya")</script>    
      @endif
    </h4>
<div class="container-admin">
    <nav class="navbar navbar-light bg-light justify-content-between">
    <a class="navbar-brand">Berita</a>
 
    <a href="{{ route('admin/berita/upload', ['token' => $user['secret']]) }}"><button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tambah</button></a>

	</nav>

  
  <div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th style="max-width: 5px">No</th>
                <th style="max-width: 400px">Judul</th>
                <th >Kategori</th>
                <th>Tanggal Upload</th>
                <th>Viewer</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody class="features">
            @php $count = ($berita->currentPage() - 1) * $berita->perPage() + 1; @endphp
            @foreach($berita as $data)
            <tr>
                <th scope="row">{{ $count }}</th>
                <td>{{ $data->judul_berita }}</td>
                <td>{{ $data->nama_kategori }}</td>
                <td>{{ $data->tanggal_upload }}</td>
                <td>{{ $data->views }}</td>
                <td>
                    <a href="{{ route('admin/berita/show', ['id' => $data->id_berita, 'token' => $user['secret'], 'status' => "publish"]) }}">
                        <button class="btn btn-primary btn-xs">Preview</button>
                    </a>
                    <a href="{{ route('admin/berita/edit', ['id' => $data->id_berita, 'token' => $user['secret']]) }}">
                        <button class="btn btn-danger btn-xs">Edit</button>
                    </a>
                </td>
            </tr>
            @php $count++ @endphp
            @endforeach
        </tbody>
    </table>
</div>
{{ $berita->links() }}
@endsection
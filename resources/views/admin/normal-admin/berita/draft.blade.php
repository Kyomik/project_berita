@extends('layouts.admin')

@section('content')
@php $user = session('user') @endphp

@if(session('status'))
  {{ session('status') }}
@endif
<div class="container-admin">
    <nav class="navbar navbar-light bg-light justify-content-between">
    <a class="navbar-brand">Berita</a>
 

	</nav>
<div class="table-wrapper">
    <table class="table">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Judul</th>
      <th scope="col">Kategori</th>
      <th>Waktu perubahan</th>
      <th>Keterangan</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody class="features">
    @php $count = 1; @endphp
    @foreach($berita as $data)
    <tr>
      <th scope="row">{{ $count }}</th>
      <td>{{ $data->judul_berita }}</td>
      <td>{{ $data->nama_kategori }}</td>
      <td>{{ $data->jumlah_hari }}</td>
      <td>{{  $data->keterangan }}</td>
  
      <td>
		      <a href="{{ route('admin/berita/show', ['id' => $data->id_berita, 'token' => $user['secret']])}}?status=draft">
		    <button class="btn btn-primary btn-xs">Preview</button>
		</a>
          <a href="{{ route('admin/berita/edit', ['id' => $data->id_berita, 'token' => $user['secret']]) }}?status=draft">
            <button class="btn btn-warning btn-xs">Edit</button>
          </a>
      </td>
    </tr>
    @php $count++ @endphp
    @endforeach
  </tbody>
</table>
</div>
</div>
<div style="display: flex; justify-content: center;">
  {{ $berita->links() }}
</div>

@endsection
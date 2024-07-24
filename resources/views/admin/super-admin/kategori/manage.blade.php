@extends('layouts.admin')

@section('content')

<div class="container-admin">
  <nav class="navbar navbar-light bg-light justify-content-between">
    <a class="navbar-brand">Kategori</a>
    <div class="feature">
      <button type="button" id="save-changes-btn" class="btn btn-primary d-none">
        Save Changes
      </button>

      <button type="button" id="save-cancel-btn" class="btn btn-primary d-none">
        Cancel ALL
      </button>
      <button id="delete-kategori-btn" class="btn btn-danger my-2 my-sm-0" type="button">Delete</button>
    </div>
  </nav>

  <div class="data-kategori">
    @foreach($kategori as $data)
  <button type="button" class="btn btn-primary kategori-btn" data-id="{{ $data->id_kategori }}" data-toggle="modal" data-target="#modal-edit">
    {{ $data->nama_kategori }}
  </button>
@endforeach

    <button id="tambah-kategori-placeholder" type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modal-tambah">
      +
    </button>
  </div>
</div>

<!-- Modal Edit-->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Kategori</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <input id="editable-input" class="form-control" type="text" placeholder="Edit kategori...">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="edit-kategori-btn">Edit</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah-->
<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input id="input-kategori" class="form-control" type="text" placeholder="Masukkan nama kategori...">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="tambah-kategori-btn">Tambah</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Delete-->
<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Kategori</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus kategori ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="confirm-delete-btn">Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection
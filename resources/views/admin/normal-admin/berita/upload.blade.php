@extends('layouts.admin')

@section('content')

<div class="container-admin">
    <form id="formUpload" method="POST" action="{{ url('api/berita/upload') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Judul</label>
            <input type="text" class="form-control" id="judulInput" name="judul_berita" placeholder="Judul...">
        </div>
        <div class="form-group">
            <label for="gambar_berita">Upload Gambar</label>
            <input type="file" class="form-control-file" id="gambar_berita" name="gambar_berita">
        </div>
        <div class="form-group" style="border: 1px solid black; padding: 10px 20px 10px 20px">
            <label style="display: block;">Pilih Kategori</label>
            @foreach($kategoris as $kategori)
                <input type="radio" class="btn-check" name="id_kategori" id="{{ $kategori->id_kategori }}" value="{{ $kategori->id_kategori }}" autocomplete="off">
                <label class="btn btn-secondary" for="{{ $kategori->id_kategori}}">{{ $kategori->nama_kategori }}</label>
            @endforeach
        </div>

        <div class="container-paragraf" id="paragrafContainer">
            <div class="form-group"> 
                <label>Paragraf 1</label>
                <textarea class="form-control" id="paragraf1" name="isi_paragraft[]" rows="3"></textarea>
            </div>
        </div>
        <button type="button" class="btn btn-primary btn-block" id="addParagrafButton">Tambah Paragraf</button>
        <div class="features">
            <button type="submit" class="btn btn-primary col-sm-1">Kirim</button>
            <button type="button" id="batalButton" class="btn btn-secondary col-sm-1" style="margin-left: 5px">Batal</button>
        </div>
    </form>
</div>


@endsection

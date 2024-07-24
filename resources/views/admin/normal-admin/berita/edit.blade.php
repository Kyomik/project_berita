@extends('layouts.admin')

@section('content')

<div class="container-admin">
    <form id="formEdit" action="{{ url('api/berita/edit', ['id_berita' => $berita->id_berita]) }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Judul</label>
            <input type="text" class="form-control" id="judulInput" name="new_judul_berita" value="{{ $berita->judul_berita }}" >
        </div>
        <div class="form-group">
            <label for="gambar_berita">Gambar</label>
            <input type="file" class="form-control-file" id="gambar_berita" name="new_gambar_berita" accept="image/*" value="{{  $berita->gambar }}">
        </div>
        <div class="form-group" style="border: 1px solid black; padding: 10px 20px 10px 20px">
            <label style="display: block;">Pilih Kategori</label>
            @foreach($kategoris as $kategori)
                <input type="radio" class="btn-check" name="id_kategori" id="{{ $kategori->id_kategori }}" value="{{ $kategori->id_kategori }}"
                    @if($kategori->id_kategori == $berita->id_kategori) checked @endif >
                <label class="btn btn-secondary" for="{{ $kategori->id_kategori}}">{{ $kategori->nama_kategori }}</label>
            @endforeach
        </div>
        <div class="container-paragraf" id="paragrafContainer">
            <div class="form-group">
                @php $counter = 1; @endphp
                @foreach($berita->paragraf ?? [] as $paragraf)
                <label>Paragraf {{ $counter }}</label>
                <input type="hidden" name="id_paragraft[]" value="{{ $paragraf->id_paragraft }}">
                <textarea class="form-control" name="isi_paragraft[]" rows="3">{{ $paragraf->isi_paragraft }}</textarea>
                @php $counter++; @endphp
                @endforeach
            </div>
        </div>
        <button type="button" class="btn btn-primary btn-block" id="addParagrafButton">Tambah Paragraf</button>
        <div class="features">
            <button type="submit" class="btn btn-primary col-sm-1">Kirim</button>
        </div>
    </form>
</div>

@endsection

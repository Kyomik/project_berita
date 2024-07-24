@extends('layouts.admin')

@section('title', 'Home')

@section('content')

@php $user = session('user') @endphp

<div class="container-admin" id="{{ $berita->id_berita }}">
    <div class="header-berita">
        <h4 class="judul-berita">{{ $berita->judul_berita }}</h4>
        <i class="tanggal-berita">{{ $berita->tanggal_berita }}</i>
    </div>
    <div class="container-berita row">
        <div class="col navv"></div>
        <div class="isi-berita col-md-9">
            @php $isFirst = true; @endphp
            @foreach($berita->paragrafs as $paragraf)
                @if($isFirst)
                    @php $isFirst = false; @endphp
                    <div class="paragraf-utama">
                        <div class="gambar-berita fix-bg-image" style="background-image: url({{ asset($berita->gambar) }})"></div>
                        <div class="paragraf-utama-text">
                            @if($paragraf->status_paragraf == "publish")
                                <p id="paragraf-utama upload">{{ $paragraf->isi_paragraf }}</p>
                            @elseif($paragraf->status_paragraf == "edit" || $paragraf->status_paragraf == "new")
                                <div class="paragraft-draft">
                                    <div class="status-paragraft"><p>{{$paragraf->status_paragraf}}</p></div>
                                    <p>{{ $paragraf->isi_paragraf }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    
                        @if($paragraf->status_paragraf == "publish")
                            <p id="paragraf-utama upload">{{ $paragraf->isi_paragraf }}</p>
                        @elseif($paragraf->status_paragraf == "edit" || $paragraf->status_paragraf == "new")
                        <div class="paragraft-draft">    
                            <div class="status-paragraft"><p>{{ $paragraf->status_paragraf }}</p></div>
                            <p class="paragraf-berita edit">{{ $paragraf->isi_paragraf }}</p>
                        </div>
                         @endif
                   
                @endif
            @endforeach
            @if($user['hak_akses'] != 'user_admin' && $user['hak_akses'] != 'admin')
                <button type="button" class="btn btn-primary btn-block" id="toggle-komentar">Buka Komentar</button>
            @endif
            </div>
        <div class="col navv"></div>
    </div>
    @if($user['hak_akses'] != 'user_admin' && $user['hak_akses'] != 'admin')
        <div class="container-komentar" style="display: none"> 
            @include('komentar')
        </div>
    @endif
</div>

@endsection

@extends($layout)

@php
    $user = session('user');
    if($user == null) { 
        $user = [
            'secret' => "",
            'nama' => "",
            'hak_akses' => ''
        ];
    }
@endphp

@section('title', 'Home')

@section('content')

<div class="container-admin" id="{{ $berita->id_berita }}">
    <div class="header-berita">
        <h4 class="judul-berita">{{ $berita->judul_berita }}</h4>
        <i class="tanggal-berita">{{ $berita->tanggal_berita }}</i>
    </div>
    <div class="container-berita row">
        <div class="col navv"></div>
        <div class="isi-berita col">
            @php $isFirst = true; @endphp
            @foreach($berita->paragrafs as $paragraf)
                @if($isFirst)
                    <div class="paragraf-utama">
                        <div class="gambar-berita fix-bg-image" style="background-image: url(' {{ asset($berita->gambar) }}')"></div>
                        <p id="paragraf-utama">{{ $paragraf->isi_paragraf }}</p>
                    </div>
                        @php $isFirst = false; @endphp
                 @else
                        <p class="paragraf-berita">{{ $paragraf->isi_paragraf }}</p>
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

<script>

</script>

@endsection

@extends('layouts.app')

@section('title', 'Home')

@section('content')

@if (session('status'))
    <script>alert("{{ session('status') }}")</script>

    @php session()->forget('status'); @endphp
@endif
    
      
        <div class="left_content">
    @foreach ($beritabyKategori as $kategori)
        <div class="single_post_content">
            <h2><span>{{ $kategori->nama_kategori }}</span></h2>

            @if (!empty($kategori->beritas))
                <div class="single_post_content_left">
                    <ul class="business_catgnav wow fadeInDown">
                        <li>
                            <figure class="bsbig_fig">
                                <a href="{{ route('berita/show', ['id' => $kategori->beritas[0]->judul_berita]) }}" class="featured_img">
                                    <img alt="" src="images/featured_img1.jpg">
                                    <span class="overlay"></span>
                                </a>
                                <figcaption>
                                    <a href="{{ route('berita/show', ['id' => $kategori->beritas[0]->judul_berita]) }}">{{ $kategori->beritas[0]->judul_berita }}</a>
                                </figcaption>
                                <p>{{ $kategori->beritas[0]->isi_paragraf }}</p>
                            </figure>
                        </li>
                    </ul>
                </div>

                <div class="single_post_content_right">
                    <ul class="spost_nav">
                        @foreach (array_slice($kategori->beritas, 1) as $berita)
                            <li>
                                <div class="media wow fadeInDown">
                                    <a href="{{ route('berita/show', ['id' => $berita->judul_berita]) }}" class="media-left">
                                        <img alt="" src="images/post_img1.jpg">
                                    </a>
                                    <div class="media-body">
                                        <a href="{{ route('berita/show', ['id' => $berita->judul_berita]) }}" class="catg_title">{{ $berita->judul_berita }}</a>
                                        <p><i class="fa fa-eye"></i> {{ $berita->views }}</p>
                                        <i >{{ $berita->tanggal_berita }}</i>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endforeach
</div>


  
@endsection
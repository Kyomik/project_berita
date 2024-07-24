@extends('layouts.app')

@section('title', 'Home')

@section('content')

<style>
    .col-lg-8.col-md-8.col-sm-8{
    }
</style>
<script></script>
<p></p>
<div class="d-flex" style="flex-direction: column">
        @foreach($beritas as $berita)
      <div class="card ">
          <div>
              <div class="col-md-4 px-0" style="height: 150px; border:1px solid black">
              </div>
              <div class="col-md-8">
                  <div class="card-body">
                      <h5 class="card-title">{{ $berita->judul_berita }}</h5>
                      <p class="card-text">
                        {{ $berita->isi_paragraft }}
                      </p>
                      <p class="card-text">
                          <small class="text-muted">{{ $berita->tanggal_berita }}</small>
                      </p>
                  </div>
              </div>
          </div>
      </div>
      @endforeach
  </div>
  <div class="pagination"></div>

@endsection
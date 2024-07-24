@extends('layouts.admin')

@section('title', 'Home')

@section('content')


            <div class="overview">
                <div class="title">
                    <i class="uil uil-tachometer-fast-alt"></i>
                    <span class="text">Dashboard</span>
                </div>

                <div class="boxes">
                    <div class="box box1">
                        <i class="uil uil-file"></i>
                        <span class="text">Total Berita</span>
                        <span class="number">{{ $totalBerita }}</span>
                    </div>
                    <div class="box box2">
                        <i class="uil uil-comments"></i>
                        <span class="text">Komentar</span>
                        <span class="number">{{ $totalKomentar }}</span>
                    </div>
                    <div class="box box3">
                        <i class="uil uil-eye"></i>
                        <span class="text">Views</span>
                        <span class="number">{{ $totalViews }}</span>
                    </div>
                </div>
                {{-- <h3>Total Berita: </h3>
                <h3>Total Komentar: </h3>
                <h3>Total Views: </h3> --}}
            
@endsection
<!DOCTYPE html>
<html>
<head>
<title>NewsFeed</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/font-awesome.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/animate.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/font.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/li-scroller.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/slick.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/jquery.fancybox.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/theme.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">

<!--[if lt IE 9]>
<script src="assets/js/html5shiv.min.js"></script>
<script src="assets/js/respond.min.js"></script>
<![endif]-->
<style type="text/css">
  
</style>
</head>
<body>
{{-- <div id="preloader">
  <div id="status">&nbsp;</div>
</div> --}}
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
<div class="container">
  <header id="header">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="header_top">
          <div class="header_top_left">
            <ul class="top_nav">
              <li><a href="{{ route('/') }}">Home</a></li>
              <li><a href="{{ route('about') }}">About</a></li>
              <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
          </div>
          <form class="header_top_right" action="{{ route('search') }}">
            <div>
              <input type="search" name="judul"  id="form1" class="form-control" />
              <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <button id="login-cuy" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalLogin">
              <span>Login</span>
            </button>
            <button id="logout-cuy" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalLogout">
              <span></span>
            </button>
          </form>
        </div>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="header_bottom">
          <div class="logo_area"><a href="{{ route('/') }}" class="logo"><img src="images/logo.jpg" alt=""></a></div>
          <div class="add_banner"><a href="#"><img src="images/addbanner_728x90_V1.jpg" alt=""></a></div>
        </div>
      </div>
    </div>
  </header>
  <section id="navArea">
    <nav class="navbar navbar-inverse" role="navigation" style="padding: 0px">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav main_nav">
          <li class="active" ><a href="{{ route('/') }}"><span class="fa fa-home desktop-home"></span><span class="mobile-show">Home</span></a></li>
          @foreach($kategoris as $kategori)
            <li style="display: inline-block;"><a href="{{ route('kategori', ['nama_kategori' => $kategori->nama_kategori]) }}">{{ $kategori->nama_kategori }}</a></li>
          @endforeach
        </ul>
      </div>
    </nav>
  </section>
  


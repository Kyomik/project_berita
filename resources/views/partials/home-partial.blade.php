<section id="newsSection">
  <div class="row">
        <div class="col-lg-12 col-md-12">
          <div class="latest_newsarea" style="background-color: black;position: relative; height:30px"> <span>Latest News</span>
            {{-- <ul id="ticker01" class="news_sticker"> --}}
              {{-- <li><a href="#"><img src="images/news_thumbnail3.jpg" alt="">My First News Item</a></li>
              <li><a href="#"><img src="images/news_thumbnail3.jpg" alt="">My Second News Item</a></li>
              <li><a href="#"><img src="images/news_thumbnail3.jpg" alt="">My Third News Item</a></li>
              <li><a href="#"><img src="images/news_thumbnail3.jpg" alt="">My Four News Item</a></li>
              <li><a href="#"><img src="images/news_thumbnail3.jpg" alt="">My Five News Item</a></li>
              <li><a href="#"><img src="images/news_thumbnail3.jpg" alt="">My Six News Item</a></li>
              <li><a href="#"><img src="images/news_thumbnail3.jpg" alt="">My Seven News Item</a></li>
              <li><a href="#"><img src="images/news_thumbnail3.jpg" alt="">My Eight News Item</a></li>
              <li><a href="#"><img src="images/news_thumbnail2.jpg" alt="">My Nine News Item</a></li> --}}
            {{-- </ul> --}}
            {{-- <div class="social_area">
              <ul class="social_nav">
                <li class="facebook"><a href="#"></a></li>
                <li class="twitter"><a href="#"></a></li>
                <li class="flickr"><a href="#"></a></li>
                <li class="pinterest"><a href="#"></a></li>
                <li class="googleplus"><a href="#"></a></li>
                <li class="vimeo"><a href="#"></a></li>
                <li class="youtube"><a href="#"></a></li>
                <li class="mail"><a href="#"></a></li>
              </ul>
            </div> --}}
        </div>
      </div>
  </section>
  <section id="sliderSection">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8">
        <div class="slick_slider">
          @foreach($beritaLatest as $berita)
          <div class="single_iteam fix-bg-image" style="background-image: url('{{ $berita->gambar }}')">
            <div class="slider_article">
              <h2><a class="slider_tittle" href="{{ route('berita/show', ['id' => $berita->judul_berita]) }}">{{$berita->judul_berita}}</a></h2>
              <p>{{$berita->isi_paragraft}}</p>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4" style="margin-bottom: 10px">
        <div class="latest_post">
          <h2><span>Latest post</span></h2>
          <div class="latest_post_container" style="overflow: hidden">
            <div id="prev-button"><i class="fa fa-chevron-up"></i></div>
            <ul class="latest_postnav" >
              @foreach($beritaLatest as $berita)
              <li>
                <div class="media truncate" > <a href="{{ route('berita/show', ['id' => $berita->judul_berita]) }}" class="media-left"> <img alt="" src="images/post_img1.jpg"> </a>
                  <div class="media-body"> 
                    <a style="font-size:14px; font-family: oswald, sans-serif" href="{{ route('berita/show', ['id' => $berita->judul_berita]) }}" class="catg_title"> {{ $berita->judul_berita }}</a> 
                    <p><i class="fa fa-eye"></i> {{ $berita->views }}</p>
                   <i >{{ $berita->tanggal_berita }}</i>
                  </div>
                  
                </div>
              </li>
              @endforeach
            </ul>
            <div id="next-button"><i class="fa  fa-chevron-down"></i></div>
          </div>
        </div>
      </div>
    </div>
  </section>
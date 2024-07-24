<div class="col-lg-4 col-md-4 col-sm-4">
        <aside class="right_content">
          <div class="single_sidebar">
            <h2><span>Popular Post</span></h2>
            <ul class="spost_nav">
              @foreach($beritaPopuler as $berita)
              <li>
                <div class="media wow fadeInDown" > <a style="background-image: url('{{ $berita->gambar }}'); margin-right:10px" href="{{ route('berita/show', ['id' => $berita->judul_berita]) }}" class="media-left"></a>
                  <div class="media-body" style="display: flex; flex-direction: column">
                    <a href="{{ route('berita/show', ['id' => $berita->judul_berita]) }}" class="catg_title">{{$berita->judul_berita}}</a> 
                    <p><i class="fa fa-eye"></i> {{ $berita->views }}</p>
                   <i >{{ $berita->tanggal_berita }}</i>
                  </div>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
          <div class="single_sidebar wow fadeInDown">
            <h2><span>Sponsor</span></h2>
            <a class="sideAdd" href="#"><img src="images/add_img.jpg" alt=""></a> </div>
          <div class="single_sidebar wow fadeInDown">
            <h2><span>Category Archive</span></h2>
            <select class="catgArchive">
              @foreach($kategoris as $kategori)
              <option>{{ $kategori->nama_kategori }}</option>
              @endforeach
            </select>
          </div>
          
        </aside>
      </div>
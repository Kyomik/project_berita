@php 
    $user = session('user');
    if($user == null) { 
        $user = [
            'secret' => null,
            'nama' => null,
            'refresh_token' => null
        ];
    }
@endphp


@include('partials/header')
    @if(request()->is('/'))
        @include('partials.home-partial')
    @endif

    <section id="contentSection">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8">
                @yield('content')
            </div>
          @include('partials/navbar')
        </div>
    </section>
  @include('partials/authmodal')
  @include('partials/footer')
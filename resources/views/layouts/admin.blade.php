@php $user = session('user') @endphp

{{-- @php 
    $user = session('user');
    if($user == null) { 
        $user = [
            'secret' => "",
            'nama' => ""
        ];
    }
@endphp --}}

@include('admin.partials.header')

@if($user)
    @if($user['hak_akses'] == "user_admin")
        @include('admin.partials.navbar-super-admin')
    @else
        @include('admin.partials.navbar-normal-admin')
    @endif
@endif
    <!--Container Main start-->
    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>

            {{-- <img src="" alt="berrak"> --}}
        </div>

        <div class="dash-content">

         @yield('content')
         </div>


    </section>
@include('admin.partials.footer')


    

    


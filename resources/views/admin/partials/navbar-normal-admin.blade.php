<nav class="nav">
    <div class="logo-name">
        <div class="logo-image" style="width: 100% !important;">
            <span class="logo_name" style="width: 100% !important; text-transform: uppercase;" >{{ $user['hak_akses'] }} {{ $user['nama'] }}</span>
        </div>
        
    </div>

    <div class="menu-items">
        <ul class="nav-links">
            <li><a href="{{ route('admin/dashboard', ['token' => $user['secret']]) }}">
                <i class="uil uil-estate"></i>
                <span class="link-name">Dashboard</span>
            </a></li>
            <li class="has-sub-menu">
                <a href="#" onclick="toggleSubMenu(event)">
                    <i class="uil uil-files-landscapes"></i>
                    <span class="link-name">Berita</span>
                </a>
                <ul class="sub-nav">
                    <li><a href="{{ route('admin/berita/publish', ['token' => $user['secret']]) }}" >
                        <i class="uil uil-file"></i>
                        <span class="link-name">Publish</span>
                    </a></li>
                    <li><a href="{{ route('admin/berita/draft', ['token' => $user['secret']]) }}">
                        <i class="uil uil-box"></i>
                        <span class="link-name">Draft</span>
                    </a></li>
                </ul>
            </li>
        </ul>
        
        <form id="logoutForm" action="{{ route('api/logout') }}">
        <ul class="logout-mode">
            <button style="border: 0px; background:none" type="submit">
            <li><a href="">
                <i class="uil uil-signout"></i>
                <span class="link-name">Logout</span>
            </a>
        </button>
            </li>
        </form>
            {{-- <li class="mode">
                <a href="#">
                    <i class="uil uil-moon"></i>
                <span class="link-name">Dark Mode</span>
                </a>

                <div class="mode-toggle">
                    <span class="switch"></span>
                </div>
            </li> --}}
        </ul>
    </div>
</nav>

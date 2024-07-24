<nav class="nav">
    <div class="logo-name">
        <div class="logo-image">
            <img src="" alt="">
        </div>
        <span class="logo_name">CodingLab</span>
    </div>

    <div class="menu-items">
        <ul class="nav-links" id="dynamic-nav-links">
            <li><a href="{{ route('admin/dashboard', ['token' => $user['secret']]) }}">
                <i class="uil uil-estate"></i>
                <span class="link-name">Dashboard</span>
            </a></li>
            <li class="has-sub-menu" id="kategori-menu">
                <a href="#" onclick="toggleSubMenuDinamis(event)">
                    <i class="uil uil-files-landscapes"></i>
                    <span class="link-name">Kategori</span>
                </a>
                <ul class="sub-nav" id="kategori-sub-nav" style="max-height: 300px;">
                    <li><a href="{{ route('admin/kategori/manage', ['token' => $user['secret']]) }}" class="btn btn-outline-info" >
                        <i class="uil uil-setting"></i>
                        <span class="link-name">Manage Kategori</span>
                    </a></li>
                    <!-- Submenu dinamis akan ditambahkan di sini -->
                </ul>
            </li>
            <li><a href="{{ route('admin/berita/draft', ['token' => $user['secret']])}}">
                <i class="uil uil-chart"></i>
                <span class="link-name">Draft</span>
            </a></li>
            <li><a href="{{ route('admin.index', ['token' => $user['secret']]) }}">
                <i class="uil uil-user"></i>
                <span class="link-name">Admin</span>
            </a></li>
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

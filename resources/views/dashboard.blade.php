<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    </style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="container">
        <div class="navigation">
            <ul>
                <li class="menu-header">
                    <span class="title"> Sweet</span>
                    <span class="title"> Sense.</span>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}">
                        <span class="icon">
                            <iconify-icon icon="solar:home-add-bold" width="40" height="40"></iconify-icon>
                        </span>
                        <span class="title"> Home </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('jurnal') }}">
                        <span class="icon">
                            <iconify-icon icon="mdi:journal" width="38" height="38"></iconify-icon>
                        </span>
                        <span class="title"> Data Jurnal</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('berita')}}">
                        <span class="icon">
                            <iconify-icon icon="material-symbols:newspaper" width="40" height="40"></iconify-icon>
                        </span>
                        <span class="title"> Data Berita </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('resep_makanan')}}">
                        <span class="icon">
                            <iconify-icon icon="uil:book-open" width="40" height="40"></iconify-icon>
                        </span>
                        <span class="title"> Data Resep Makanan </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('kategori_gula')}}">
                        <span class="icon">
                            <iconify-icon icon="healthicons:sugar-outline" width="58" height="58"></iconify-icon>
                        </span>
                        <span class="title"> Data kategori Gula</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main">
            <div class="card">
                <div class="box">
                    <div class="topbar">
                        <div class="text1">
                            <span class="tittle"> Dashboard</span>
                        </div>
                        <div class="text2" style="display: flex; align-items: center; gap: 10px;">
                            <span class="tittle">Welcome, {{ Auth::guard('admin')->user()->nama ?? 'Guest' }}!</span>
                            <div class="profile-dropdown">
                                <button id="profileBtn" style="background: none; border: none; cursor: pointer;">
                                    <iconify-icon icon="ix:user-profile-filled" width="50" height="50"></iconify-icon>
                                </button>
                                <div id="dropdownMenu" class="dropdown-content">
                                    <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit">
                                            <iconify-icon icon="material-symbols:logout" width="20"></iconify-icon>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box2">
                        <div class="title1">
                            <span>Hello, {{ Auth::guard('admin')->user()->nama ?? 'Guest' }}</span><br>
                            <span class="child">Make each day your masterpiece with Sweet Sense</span>
                        </div>
                        <div class="gambar">
                            <img src="/images/gambar1.png" alt=""></img>
                        </div>
                    </div>
                    <div class="topbar">
                        <div class="text1">
                            <span class="tittle"> Dashboard</span>
                        </div>
                    </div>
                    <div class="box3">
                        <div class="card3">
                            <a href="{{ route('jurnal') }}">
                                <span class="title2">01</span>
                                <iconify-icon icon="mdi:journal" width="50" height="50" style="color: #fff">
                                </iconify-icon>
                                <span class="title3">Data Jurnal</span>
                                <span class="title3">{{ $jurnalCount }} Data</span>
                            </a>
                        </div>
                        <div class="card3">
                            <a href="{{route('berita')}}">
                                <span class="title2">02</span>
                                <iconify-icon icon="material-symbols:newspaper" width="50" height="50"
                                    style="color: #fff"></iconify-icon>
                                <span class="title3">Data Berita</span>
                                <span class="title3">{{ $beritaCount }} Data</span>
                            </a>
                        </div>
                        <div class="card3">
                            <a href="{{route('resep_makanan')}}">
                                <span class="title2">03</span>
                                <iconify-icon icon="uil:book-open" width="50" height="50" style="color: #fff">
                                </iconify-icon>
                                <span class="title3">Data Resep Makanan</span>
                                <span class="title3">{{ $resepCount }} Data</span>
                            </a>
                        </div>
                        <div class="card3">
                            <a href="{{route('kategori_gula')}}">
                                <span class="title2">04</span>
                                <iconify-icon icon="healthicons:sugar-outline" width="60" height="60"
                                    style="color: #fff"></iconify-icon>
                                <span class="title3">Data Kategori Gula</span>
                                <span class="title3">{{ $kategoriGulaCount }} Data</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/profile_logout.js') }}"></script>
</body>

</html>

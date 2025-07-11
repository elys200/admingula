<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Jurnal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="container">
        <div class="navigation">
            <ul>
                <li class="menu-header">
                    <span class="title">Sweet</span>
                    <span class="title">Sense.</span>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}">
                        <span class="icon">
                            <iconify-icon icon="solar:home-add-bold" width="40" height="40"></iconify-icon>
                        </span>
                        <span class="title">Home</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('jurnal') }}">
                        <span class="icon">
                            <iconify-icon icon="mdi:journal" width="38" height="38"></iconify-icon>
                        </span>
                        <span class="title">Data Jurnal</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('berita')}}">
                        <span class="icon">
                            <iconify-icon icon="material-symbols:newspaper" width="40" height="40"></iconify-icon>
                        </span>
                        <span class="title">Data Berita</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('resep_makanan')}}">
                        <span class="icon">
                            <iconify-icon icon="uil:book-open" width="40" height="40"></iconify-icon>
                        </span>
                        <span class="title">Data Resep Makanan</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('kategori_gula')}}">
                        <span class="icon">
                            <iconify-icon icon="healthicons:sugar-outline" width="58" height="58"></iconify-icon>
                        </span>
                        <span class="title">Data kategori Gula</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="main">
            <div class="card">
                <div class="box">
                    <div class="topbar">
                        <div class="text1">
                            <span class="tittle">Data Jurnal</span>
                        </div>
                        <div class="text2">
                            <span class="tittle">Welcome, {{ Auth::user()->name ?? 'Guest' }}!</span>
                            <iconify-icon icon="ix:user-profile-filled" width="50" height="50"></iconify-icon>
                        </div>
                    </div>
                    
                    <div class="topbar2">
                        <div class="search-wrapper">
                            <iconify-icon icon="material-symbols:search-rounded" width="24" height="24"
                                class="search-icon" onclick="toggleSearch()"></iconify-icon>
                            <input type="text" id="searchInput" placeholder="Cari data jurnal..." class="search-input"
                                oninput="handleSearch(this)" />
                        </div>
                    </div>
                    
                    <div class="card4">
                        <table class="striped-table">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Pengguna</td>
                                    <td>Jumlah Konsumsi</td>
                                    <td>Tanggal</td>
                                    <td>Status Gula</td>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @forelse ($jurnal as $j)
                                <tr>
                                    <td>{{ $j->id }}</td>
                                    <td>
                                        <div class="tabel-truncate" title="{{ $j->user->username ?? '-' }}">
                                            {{ $j->user->username ?? '-' }}
                                        </div>
                                    </td>
                                    <td>{{ $j->total_gula }} gram</td>
                                    <td>{{ $j->date }}</td>
                                    <td>
                                        <div class="tabel-truncate" title="{{ $j->kategori->nama ?? '-' }}">
                                            {{ $j->kategori->nama ?? '-' }}
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="text-align:center;">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div id="pagination" class="pagination">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jurnals.js') }}"></script>
</body>

</html>
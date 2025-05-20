<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Data Jurnal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    </style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
</head>

<body>
    <div class="container">
        <div class="navigation">
            <ul>
                <li class="menu-header">
                    <span class="title"> Sweet</span>
                    <span class="title"> Seense.</span>
                </li>
                <li>
                    <a href="">
                        <span class="icon">
                            <iconify-icon icon="ix:user-profile-filled" width="40" height="40"></iconify-icon>
                        </span>
                        <span class="title"> Profile</span>
                    </a>
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
                    <a href="{{route('resep')}}">
                        <span class="icon">
                            <iconify-icon icon="uil:book-open" width="40" height="40"></iconify-icon>
                        </span>
                        <span class="title"> Data Resep Makanan </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('kategorigula')}}">
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
                            <span class="tittle"> Data Berita</span>
                        </div>
                        <div class="text2">
                            <span class="tittle"> Welcome, Han!</span>
                            <iconify-icon icon="ix:user-profile-filled" width="50" height="50"></iconify-icon>
                        </div>
                    </div>
                    <div class="topbar2">
                        <button class="tambah">
                            <iconify-icon icon="stash:plus-solid" width="24" height="24" style="color: #fff;"></iconify-icon>
                            <span class="btn-text">Tambah Data</span>
                        </button>
                        <div class="search-box">
                            <iconify-icon icon="material-symbols:search-rounded" width="24" height="24"></iconify-icon>
                        </div>
                    </div>
                    <div class="card4">
                        <table class="striped-table">
                            <tr>
                                <td>ID</td>
                                <td>Judul</td>
                                <td>Sumber</td>
                                <td>Deskripsi</td>
                                <td>Penulis</td>
                                <td>Tanggal Terbit</td>
                                <td>Gambar</td>
                                <td>Aksi</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Studi: Diet Rendah Karbohidrat Efektif Turunkan dalam 3 Bulan</td>
                                <td>Journal of Nutrition</td>
                                <td>Penelitian di Universitas Harvard membuktikan diet keto terkontrol bisa mengurangi HbA1c hingga 1.5% pada penderita prediabetes.</td>
                                <td>Prof. Sarah Tanuwijaya, Ph.D</td>
                                <td>2024-08-15</td>
                                <td>
                                    <img src="/images/gambar2.png" alt="">
                                </td>
                                <td>
                                    <div class="icon-group">
                                        <iconify-icon icon="uil:edit" width="24" height="24"  style="color: #E9B310"></iconify-icon>
                                        <iconify-icon icon="heroicons:trash-16-solid" width="24" height="24"  style="color: #E43A15">
                                      </div>                                      
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

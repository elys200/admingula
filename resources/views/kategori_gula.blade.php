<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Data Kategori Gula</title>
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
                            <span class="tittle"> Data Kategori Gula</span>
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
                    <div class="topbar2">
                        <button class="tambah" onclick="openModal('add')">
                            <iconify-icon icon="stash:plus-solid" width="24" height="24" style="color: #fff;">
                            </iconify-icon>
                            <span class="btn-text">Tambah Data</span>
                        </button>
                        <div class="search-box">
                            <iconify-icon icon="material-symbols:search-rounded" width="24" height="24"></iconify-icon>
                        </div>
                    </div>

                    <div class="card4">
                        <table class="striped-table">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Nama</td>
                                    <td>Gula Minimal</td>
                                    <td>Gula Maksimal</td>
                                    <td>Deskripsi</td>
                                    <td>Aksi</td>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @foreach ($kategori as $kg)
                                <tr>
                                    <td>{{ $kg->id }}</td>
                                    <td>
                                        <div id="judul-{{ $kg->id }}" title="{{ $kg->nama }}">
                                            {{ $kg->nama }}</div>
                                    </td>
                                    <td>{{ $kg->gula_min }}</td>
                                    <td>{{ $kg->gula_max }}</td>
                                    <td>{{ $kg->deskripsi }}</td>
                                    <td>
                                        <div class="icon-group">
                                            <iconify-icon icon="uil:edit" width="24" style="color: #E9B310"
                                                onclick='openModal("edit", {{ $kg->id }})'></iconify-icon>
                                            <iconify-icon icon="heroicons:trash-16-solid" width="24"
                                                style="color: #E43A15"
                                                onclick="openDeleteModal({{ $kg->id }}, '{{ addslashes($kg->nama) }}')">
                                            </iconify-icon>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Pop-up -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Kategori Gula Baru</h3>
                <div class="modal-close" onclick="closeModal()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>

            <form id="modalForm" class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="namaInput">Nama Kategori</label>
                    <select class="form-input" id="namaInput" required>
                        <option value="">-- Pilih --</option>
                        <option value="low">Low</option>
                        <option value="normal">Normal</option>
                        <option value="high">High</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="gulaMinInput">Gula Minimal</label>
                    <input class="form-input" type="text" id="gulaMinInput" placeholder="Masukkan batas minimal gula" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="gulaMaxInput">Gula Maksimal</label>
                    <input class="form-input" type="text" id="gulaMaxInput"
                        placeholder="Masukkan batas maksimal gula" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="deskripsiInput">Deskripsi (Opsional)</label>
                    <textarea class="form-textarea" id="deskripsiInput" placeholder="Masukkan deskripsi (jika ada)"
                        rows="3"></textarea>
                </div>
            </form>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" onclick="closeModal()">Batal</button>
                <button class="btn btn-primary" type="button" onclick="saveData()" id="saveButton">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Modal Delete Popup -->
    <div id="deleteModalOverlay">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <p>Hapus Kategori</p>
            <p>Apakah kamu yakin ingin menghapus kategori ini? <span id="deleteTitle"></span>?</p>
            <div class="button-container">
                <button id="confirmDeleteBtn">Ya, hapus kategori</button>
                <button id="cancelDeleteBtn">Tidak</button>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/kategori.js') }}"></script>
    <script src="{{ asset('js/profile_logout.js') }}"></script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Data Resep Makanan</title>
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
                            <span class="tittle"> Data Resep Makanan </span>
                        </div>
                        <div class="text2">
                            <span class="tittle"> Welcome, Han!</span>
                            <iconify-icon icon="ix:user-profile-filled" width="50" height="50"></iconify-icon>
                        </div>
                    </div>
                    <div class="topbar2">
                        <button class="tambah" onclick="openModal('add')">
                            <iconify-icon icon="stash:plus-solid" width="24" height="24" style="color: #fff;">
                            </iconify-icon>
                            <span class="btn-text">Tambah Data</span>
                        </button>
                        <div class="search-wrapper">
                            <iconify-icon icon="material-symbols:search-rounded" width="24" height="24"
                                class="search-icon" onclick="toggleSearch()"></iconify-icon>
                            <input type="text" id="searchInput" placeholder="Cari nama resep..." class="search-input"
                                oninput="handleSearch(this)" />
                        </div>
                    </div>

                    <div class="card4">
                        <table class="striped-table">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Nama</td>
                                    <td>Deskripsi</td>
                                    <td>Panduan</td>
                                    <td>Total Kalori</td>
                                    <td>Total Karbohidrat</td>
                                    <td>Total Lemak</td>
                                    <td>Kadar Gula</td>
                                    <td>Bahan</td>
                                    <td>Tips</td>
                                    <td>Gambar</td>
                                    <td>Aksi</td>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($resep as $r)
                                <tr>
                                    <td>{{ $r->id }}</td>
                                    <td>
                                        <div id="judul-{{ $r->id }}" class="tabel-truncate" title="{{ $r->nama }}">
                                            {{ $r->nama }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="tabel-truncate" title="{{ $r->deskripsi }}">{{ $r->deskripsi }}
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                        $panduanData = $r->panduan;
                                        if (is_string($panduanData)) {
                                        $panduanArray = json_decode($panduanData, true);
                                        $panduanData = is_array($panduanArray) ? $panduanArray : [$panduanData];
                                        } elseif (!is_array($panduanData)) {
                                        $panduanData = [$panduanData];
                                        }
                                        $panduanText = implode(', ', array_filter($panduanData));
                                        @endphp
                                        <div class="tabel-truncate" title="{{ $panduanText }}">
                                            {{ $panduanText }}
                                        </div>
                                    </td>
                                    <td>{{ $r->total_kalori }}</td>
                                    <td>{{ $r->total_karbohidrat }}</td>
                                    <td>{{ $r->total_lemak }}</td>
                                    <td>{{ $r->kadar_gula }}</td>
                                    <td>
                                        @php
                                        $bahanData = $r->bahan;
                                        if (is_string($bahanData)) {
                                        $bahanArray = json_decode($bahanData, true);
                                        $bahanData = is_array($bahanArray) ? $bahanArray : [$bahanData];
                                        } elseif (!is_array($bahanData)) {
                                        $bahanData = [$bahanData];
                                        }
                                        $bahanText = implode(', ', array_filter($bahanData));
                                        @endphp
                                        <div class="tabel-truncate" title="{{ $bahanText }}">
                                            {{ $bahanText }}
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                        $tipsData = $r->tips;
                                        if (is_string($tipsData)) {
                                        $tipsArray = json_decode($tipsData, true);
                                        $tipsData = is_array($tipsArray) ? $tipsArray : [$tipsData];
                                        } elseif (!is_array($tipsData)) {
                                        $tipsData = [$tipsData];
                                        }
                                        $tipsText = implode(', ', array_filter($tipsData));
                                        @endphp
                                        <div class="tabel-truncate" title="{{ $tipsText }}">
                                            {{ $tipsText }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($r->gambar)
                                        <img src="{{ asset('storage/' . $r->gambar) }}" alt="Gambar" width="60"
                                            style="border-radius: 4px;">
                                        @else
                                        Tidak ada
                                        @endif
                                    </td>
                                    <td>
                                        <div class="icon-group">
                                            <iconify-icon icon="uil:edit" width="24"
                                                style="color: #E9B310; cursor: pointer;"
                                                onclick='openModal("edit", {{ $r->id }}, @json($r))'></iconify-icon>
                                            <iconify-icon icon="heroicons:trash-16-solid" width="24"
                                                style="color: #E43A15; cursor: pointer;"
                                                onclick="openDeleteModal({{ $r->id }}, '{{ addslashes($r->nama) }}')">
                                            </iconify-icon>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="pagination" class="pagination"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Pop-up -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Resep Baru</h3>
                <div class="modal-close" onclick="closeModal()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="namaInput">Nama Resep</label>
                    <input class="form-input" type="text" id="namaInput" placeholder="Masukkan nama resep" />
                </div>
                <div class="form-group">
                    <label class="form-label" for="deskripsiInput">Deskripsi</label>
                    <textarea class="form-textarea" id="deskripsiInput" placeholder="Masukkan deskripsi resep"
                        rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="panduanInput">Panduan Memasak</label>
                    <textarea class="form-textarea" id="panduanInput"
                        placeholder="Siapkan ikan&#10;Bersihkan isi perut&#10;Tambahkan bumbu" rows="5"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="kaloriInput">Total Kalori</label>
                    <input class="form-input" type="text" id="kaloriInput" placeholder="Masukkan total kalori" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="karbohidratInput">Total Karbohidrat</label>
                    <input class="form-input" type="text" id="karbohidratInput"
                        placeholder="Masukkan total karbohidrat" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="lemakInput">Total Lemak</label>
                    <input class="form-input" type="text" id="lemakInput" placeholder="Masukkan total lemak" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="gulaInput">Kadar Gula</label>
                    <input class="form-input" type="text" id="gulaInput" placeholder="Masukkan kadar gula" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="bahanInput">Bahan-bahan</label>
                    <textarea class="form-textarea" id="bahanInput"
                        placeholder="Ikan 500 gram&#10;Bumbu dasar 2 sendok&#10;Garam secukupnya" rows="5"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="tipsInput">Tips Memasak</label>
                    <textarea class="form-textarea" id="tipsInput"
                        placeholder="Gunakan api sedang&#10;Jangan terlalu lama menggoreng&#10;Tambahkan daun bawang di akhir"
                        rows="5"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="gambarInput">Gambar</label>
                    <input class="form-input" type="file" id="gambarInput" accept="image/*" />
                    <img id="imagePreview" src="" alt="Preview Gambar"
                        style="max-width: 200px; margin-top: 10px; display: none;" />
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal()">Batal</button>
                <button class="btn btn-primary" onclick="saveData()" id="saveButton">Simpan Resep</button>
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
            <p>Hapus Berita</p>
            <p>Apakah kamu yakin ingin menghapus resep ini? <span id="deleteTitle"></span>?</p>
            <div class="button-container">
                <button id="confirmDeleteBtn">Ya, hapus resep</button>
                <button id="cancelDeleteBtn">Tidak</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/resep.js') }}"></script>
</body>

</html>

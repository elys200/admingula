// berita.js (versi refactor sesuai pendekatan ResepController)

let currentMode = "add";
let currentEditId = null;
let deleteTargetId = null;
let currentPage = 1;
let currentSearchQuery = "";
let isLoading = false;

// Buka Modal Tambah / Edit
async function openModal(mode, id = null) {
    currentMode = mode;
    currentEditId = id;

    document.getElementById("modalTitle").textContent =
        mode === "add" ? "Tambah Berita Baru" : "Edit Berita";
    document.getElementById("saveButton").textContent =
        mode === "add" ? "Simpan" : "Update";

    clearForm();

    if (mode === "edit" && id) {
        try {
            const res = await fetch(`/api/v1/berita/${id}`);
            if (!res.ok) throw new Error("Data tidak ditemukan");
            const { data } = await res.json();

            document.getElementById("judulInput").value = data.judul || "";
            document.getElementById("sumberInput").value = data.sumber || "";
            document.getElementById("deskripsiInput").value =
                data.deskripsi || "";
            document.getElementById("penulisInput").value = data.penulis || "";
            document.getElementById("tanggalInput").value =
                data.tanggalterbit || "";
            document.getElementById("linkInput").value = data.link || "";

            const kategoriValue = (data.kategori || "").toLowerCase();
            const select = document.getElementById("kategoriInput");
            [...select.options].forEach((option) => {
                option.selected = option.value.toLowerCase() === kategoriValue;
            });

            const imgPreview = document.getElementById("imagePreview");
            if (data.gambar) {
                imgPreview.src = `/storage/${data.gambar}`;
                imgPreview.style.display = "block";
            }
        } catch (err) {
            console.error(err);
            alert("Gagal memuat data untuk diedit.");
        }
    }

    document.getElementById("modalOverlay").classList.add("active");
}

function closeModal() {
    document.getElementById("modalOverlay").classList.remove("active");
    clearForm();
}

function clearForm() {
    [
        "judulInput",
        "sumberInput",
        "deskripsiInput",
        "penulisInput",
        "kategoriInput",
        "tanggalInput",
        "linkInput",
        "gambarInput",
    ].forEach((id) => {
        document.getElementById(id).value = "";
    });
    const preview = document.getElementById("imagePreview");
    preview.src = "";
    preview.style.display = "none";
}

async function saveData() {
    const formData = new FormData();
    formData.append("judul", document.getElementById("judulInput").value);
    formData.append("sumber", document.getElementById("sumberInput").value);
    formData.append(
        "deskripsi",
        document.getElementById("deskripsiInput").value
    );
    formData.append("penulis", document.getElementById("penulisInput").value);
    formData.append("kategori", document.getElementById("kategoriInput").value);
    formData.append(
        "tanggalterbit",
        document.getElementById("tanggalInput").value
    );
    formData.append("link", document.getElementById("linkInput").value);

    const gambar = document.getElementById("gambarInput").files[0];
    if (gambar) formData.append("gambar", gambar);

    if (currentMode === "edit") formData.append("_method", "PUT");

    try {
        const res = await fetch(
            currentMode === "add"
                ? "/api/v1/berita"
                : `/api/v1/berita/${currentEditId}`,
            {
                method: "POST",
                body: formData,
            }
        );

        if (!res.ok) {
            const errText = await res.text();
            throw new Error(errText);
        }

        const result = await res.json();
        alert(result.message || "Data berhasil disimpan.");
        closeModal();
        loadBerita(currentPage);
    } catch (err) {
        console.error(err);
        alert("Terjadi kesalahan saat menyimpan data.");
    }
}

function openDeleteModal(id, judul) {
    deleteTargetId = id;
    document.getElementById("deleteTitle").textContent = `"${judul}"`;
    document.getElementById("deleteModalOverlay").style.display = "flex";
}

function closeDeleteModal() {
    deleteTargetId = null;
    document.getElementById("deleteModalOverlay").style.display = "none";
}

async function confirmDelete() {
    if (!deleteTargetId) return;
    try {
        const res = await fetch(`/api/v1/berita/${deleteTargetId}`, {
            method: "DELETE",
        });

        if (!res.ok) throw new Error("Gagal menghapus data.");
        const data = await res.json();
        alert(data.message || "Data berhasil dihapus!");
        closeDeleteModal();
        loadBerita(currentPage);
    } catch (err) {
        console.error(err);
        alert("Gagal menghapus data.");
    }
}

async function loadBerita(page = 1) {
    if (isLoading) return;
    isLoading = true;
    currentPage = page;

    const tableBody = document.querySelector("#table-body");
    if (tableBody) {
        tableBody.innerHTML = `<tr><td colspan="100%" style="text-align:center">Loading...</td></tr>`;
    }

    const searchParam = currentSearchQuery
        ? `&search=${encodeURIComponent(currentSearchQuery)}`
        : "";

    try {
        const res = await fetch(`/api/v1/berita?page=${page}${searchParam}`);
        const data = await res.json();
        updateTableContent(data.data);
        renderPagination(data);
    } catch (err) {
        console.error("Error loading berita:", err);
        if (tableBody)
            tableBody.innerHTML = `<tr><td colspan="100%" style="text-align:center">Error loading data</td></tr>`;
    } finally {
        isLoading = false;
    }
}

function updateTableContent(data) {
    const tableBody = document.getElementById("table-body");
    if (!tableBody) return;

    if (!data || data.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="100%" style="text-align:center">Tidak ada data ditemukan</td></tr>`;
        return;
    }

    tableBody.innerHTML = data
        .map((item) => {
            return `
            <tr>
                <td>${item.id}</td>
                <td><div class="tabel-truncate" title="${item.judul ?? ""}">${
                item.judul ?? ""
            }</div></td>
                <td><div class="tabel-truncate" title="${item.sumber ?? ""}">${
                item.sumber ?? ""
            }</div></td>
                <td><div class="tabel-truncate" title="${
                    item.deskripsi ?? ""
                }">${item.deskripsi ?? ""}</div></td>
                <td><div class="tabel-truncate" title="${item.penulis ?? ""}">${
                item.penulis ?? ""
            }</div></td>
                <td><div class="tabel-truncate" title="${
                    item.kategori ?? ""
                }">${item.kategori ?? ""}</div></td>
                <td>${item.tanggalterbit ?? ""}</td>
                <td><div class="tabel-truncate" title="${item.link ?? ""}">${
                item.link ?? ""
            }</div></td>
                <td>
                    ${
                        item.gambar
                            ? `<img src="/storage/${item.gambar}" alt="Gambar" width="60">`
                            : "Tidak ada"
                    }
                </td>
                <td>
                    <div class="icon-group">
                        <iconify-icon icon="uil:edit" width="24" style="color: #E9B310"
                            onclick="openModal('edit', ${
                                item.id
                            })"></iconify-icon>
                        <iconify-icon icon="heroicons:trash-16-solid" width="24" style="color: #E43A15"
                            onclick="openDeleteModal(${
                                item.id
                            }, '${item.judul.replace(/'/g, "\\'")}')">
                        </iconify-icon>
                    </div>
                </td>
            </tr>
        `;
        })
        .join("");

    if (window.Iconify && Iconify.scan) {
        Iconify.scan();
    }
}

function renderPagination(data) {
    const container = document.getElementById("pagination");
    if (!container) return;

    const totalPages = data.last_page || 1;
    const currentPageNum = data.current_page || 1;
    let html = `<button class="page-button" ${
        currentPageNum <= 1 ? "disabled" : ""
    } onclick="loadBerita(${currentPageNum - 1})">&lt;</button>`;

    const startPage = Math.max(1, currentPageNum - 2);
    const endPage = Math.min(totalPages, startPage + 4);

    for (let i = startPage; i <= endPage; i++) {
        html += `<button class="page-button ${
            i === currentPageNum ? "active" : ""
        }" onclick="loadBerita(${i})">${i}</button>`;
    }

    html += `<button class="page-button" ${
        currentPageNum >= totalPages ? "disabled" : ""
    } onclick="loadBerita(${currentPageNum + 1})">&gt;</button>`;

    container.innerHTML = html;
}

function handleSearch(input) {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        currentSearchQuery = input.value.trim();
        currentPage = 1;
        loadBerita(1);
    }, 400);
}

function toggleSearch() {
    const wrapper = document.querySelector(".search-wrapper");
    if (!wrapper) return;

    wrapper.classList.toggle("active");
    const input = document.getElementById("searchInput");
    if (wrapper.classList.contains("active")) {
        setTimeout(() => input?.focus(), 100);
    } else {
        if (input) input.value = "";
        currentSearchQuery = "";
        loadBerita(1);
    }
}

// Event Listeners

document
    .getElementById("cancelDeleteBtn")
    .addEventListener("click", closeDeleteModal);
document
    .getElementById("confirmDeleteBtn")
    .addEventListener("click", confirmDelete);
document.getElementById("gambarInput").addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
        const preview = document.getElementById("imagePreview");
        preview.src = URL.createObjectURL(file);
        preview.style.display = "block";
    }
});

document.addEventListener("DOMContentLoaded", function () {
    loadBerita(1);
});

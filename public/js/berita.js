let currentMode = "add";
let currentEditId = null;
let deleteTargetId = null; // ID berita yang akan dihapus

// Fungsi buka modal tambah/edit
function openModal(mode, id = null) {
    currentMode = mode;
    currentEditId = id;

    // Judul dan tombol modal
    document.getElementById("modalTitle").textContent =
        mode === "add" ? "Tambah Berita Baru" : "Edit Berita";
    document.getElementById("saveButton").textContent =
        mode === "add" ? "Simpan" : "Update";

    if (mode === "add") {
        clearForm();
    }

    if (mode === "edit") {
        fetch(`/api/berita/${id}`)
            .then((res) => {
                if (!res.ok) throw new Error("Data tidak ditemukan");
                return res.json();
            })
            .then((response) => {
                const data = response.data;

                document.getElementById("judulInput").value = data.judul || "";
                document.getElementById("sumberInput").value =
                    data.sumber || "";
                document.getElementById("deskripsiInput").value =
                    data.deskripsi || "";
                document.getElementById("penulisInput").value =
                    data.penulis || "";
                document.getElementById("tanggalInput").value =
                    data.tanggalterbit || "";

                const imgPreview = document.getElementById("imagePreview");
                if (data.gambar) {
                    imgPreview.src = `/storage/${data.gambar}`;
                    imgPreview.style.display = "block";
                } else {
                    imgPreview.src = "";
                    imgPreview.style.display = "none";
                }
            })
            .catch((err) => {
                alert("Gagal memuat data untuk diedit.");
                console.error(err);
            });
    }

    document.getElementById("modalOverlay").classList.add("active");
}

// Fungsi tutup modal tambah/edit
function closeModal() {
    document.getElementById("modalOverlay").classList.remove("active");
    clearForm();
}

// Bersihkan form tambah/edit
function clearForm() {
    document.getElementById("judulInput").value = "";
    document.getElementById("sumberInput").value = "";
    document.getElementById("deskripsiInput").value = "";
    document.getElementById("penulisInput").value = "";
    document.getElementById("tanggalInput").value = "";
    document.getElementById("gambarInput").value = "";

    const imgPreview = document.getElementById("imagePreview");
    imgPreview.src = "";
    imgPreview.style.display = "none";
}

// Simpan data tambah/edit
function saveData() {
    const formData = new FormData();
    formData.append("judul", document.getElementById("judulInput").value);
    formData.append("sumber", document.getElementById("sumberInput").value);
    formData.append(
        "deskripsi",
        document.getElementById("deskripsiInput").value
    );
    formData.append("penulis", document.getElementById("penulisInput").value);
    formData.append(
        "tanggalterbit",
        document.getElementById("tanggalInput").value
    );

    const gambar = document.getElementById("gambarInput").files[0];
    if (gambar) {
        formData.append("gambar", gambar);
    }

    const url =
        currentMode === "add" ? "/api/berita" : `/api/berita/${currentEditId}`;
    const method = "POST";

    if (currentMode === "edit") {
        formData.append("_method", "PUT");
    }

    fetch(url, {
        method: method,
        body: formData,
    })
        .then(async (res) => {
            if (!res.ok) {
                const errText = await res.text();
                alert("Terjadi kesalahan pada server:\n" + errText);
                console.error("Server error response:", errText);
                throw new Error("Gagal dari server.");
            }
            return res.json();
        })
        .then((data) => {
            alert(data.message || "Berhasil menyimpan data!");
            location.reload();
        })
        .catch((err) => {
            console.error("Fetch error:", err);
            alert("Gagal menyimpan data. Cek konsol.");
        });
}

// Buka modal konfirmasi hapus
function openDeleteModal(id) {
    deleteTargetId = id;

    const titleEl = document.getElementById(`judul-${id}`);
    const judul = titleEl ? titleEl.textContent.trim() : "data ini";

    document.getElementById("deleteTitle").textContent = `"${judul}"`;

    const modal = document.getElementById("deleteModalOverlay");
    modal.style.display = "flex"; 
}

// Tutup modal konfirmasi hapus
function closeDeleteModal() {
    deleteTargetId = null;
    document.getElementById("deleteModalOverlay").style.display = "none";
}

// Konfirmasi hapus data
function confirmDelete() {
    if (!deleteTargetId) return;

    fetch(`/api/berita/${deleteTargetId}`, {
        method: "DELETE",
    })
        .then((res) => {
            if (!res.ok) throw new Error("Gagal menghapus data.");
            return res.json();
        })
        .then((data) => {
            alert(data.message || "Data berhasil dihapus!");
            closeDeleteModal();
            location.reload();
        })
        .catch((err) => {
            console.error(err);
            alert("Gagal menghapus data.");
            closeDeleteModal();
        });
}


// Event Listener tombol modal hapus
document.getElementById("cancelDeleteBtn").addEventListener("click", () => {
    closeDeleteModal();
});

document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
    confirmDelete();
});

// --- Preview gambar saat input file diubah ---
document.getElementById("gambarInput").addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
        const preview = document.getElementById("imagePreview");
        preview.src = URL.createObjectURL(file);
        preview.style.display = "block";
    }
});

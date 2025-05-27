let currentMode = "add";
let currentEditId = null;
let deleteTargetId = null; // ID berita yang akan dihapus

function openModal(mode, id = null) {
    currentMode = mode;
    currentEditId = id;

    document.getElementById("modalTitle").textContent =
        mode === "add" ? "Tambah Resep Baru" : "Edit Resep";
    document.getElementById("saveButton").textContent =
        mode === "add" ? "Simpan" : "Update";

    if (mode === "add") {
        clearForm();
    }

    if (mode === "edit") {
        fetch(`/api/resep/${id}`)
            .then((res) => {
                if (!res.ok) throw new Error("Data tidak ditemukan");
                return res.json();
            })
            .then((response) => {
                const data = response.data;

                document.getElementById("namaInput").value = data.nama || "";
                document.getElementById("deskripsiInput").value =
                    data.deskripsi || "";
                document.getElementById("panduanInput").value =
                    data.panduan || "";
                document.getElementById("bahanInput").value = data.bahan || "";
                document.getElementById("kaloriInput").value =
                    data.total_kalori || "";
                document.getElementById("gulaInput").value =
                    data.kadar_gula || "";

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
    document.getElementById("namaInput").value = "";
    document.getElementById("deskripsiInput").value = "";
    document.getElementById("panduanInput").value = "";
    document.getElementById("bahanInput").value = "";
    document.getElementById("kaloriInput").value = "";
    document.getElementById("gulaInput").value = "";
    document.getElementById("gambarInput").value = "";

    const imgPreview = document.getElementById("imagePreview");
    imgPreview.src = "";
    imgPreview.style.display = "none";
}

function saveData() {
    const formData = new FormData();
    formData.append("nama", document.getElementById("namaInput").value);
    formData.append(
        "deskripsi",
        document.getElementById("deskripsiInput").value
    );
    formData.append("panduan", document.getElementById("panduanInput").value);
    formData.append("bahan", document.getElementById("bahanInput").value);
    formData.append(
        "total_kalori",
        document.getElementById("kaloriInput").value
    );
    formData.append("kadar_gula", document.getElementById("gulaInput").value);

    const gambar = document.getElementById("gambarInput").files[0];
    if (gambar) {
        formData.append("gambar", gambar);
    }

    const url =
        currentMode === "add" ? "/api/resep" : `/api/resep/${currentEditId}`;
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

document.getElementById("gambarInput").addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
        const preview = document.getElementById("imagePreview");
        preview.src = URL.createObjectURL(file);
        preview.style.display = "block";
    }
});

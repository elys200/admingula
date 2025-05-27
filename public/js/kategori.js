let currentMode = "add";
let currentEditId = null;
let deleteTargetId = null; // ID berita yang akan dihapus

function openModal(mode, id = null) {
    currentMode = mode;
    currentEditId = id;

    document.getElementById("modalTitle").textContent =
        mode === "add" ? "Tambah Kategori Gula Baru" : "Edit Kategori Gula";
    document.getElementById("saveButton").textContent =
        mode === "add" ? "Simpan" : "Update";

    if (mode === "add") {
        clearForm();
    } else {
        document.getElementById("modalOverlay").classList.add("loading");
        fetch(`/api/kategori_gula/${id}`)
            .then((res) => {
                if (!res.ok) throw new Error("Data tidak ditemukan");
                return res.json();
            })
            .then((response) => {
                const data = response.data;
                document.getElementById("namaInput").value = data.nama || "";
                document.getElementById("gulaMinInput").value =
                    data.gula_min || "";
                document.getElementById("gulaMaxInput").value =
                    data.gula_max || "";
                document.getElementById("deskripsiInput").value =
                    data.deskripsi || "";
            })
            .catch((err) => {
                alert("Gagal memuat data untuk diedit.");
                console.error(err);
            })
            .finally(() => {
                document
                    .getElementById("modalOverlay")
                    .classList.remove("loading");
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
    document
        .querySelectorAll("#modalForm input, #modalForm textarea")
        .forEach((input) => {
            input.value = "";
        });
}

// Simpan data tambah/edit
function saveData() {
    const nama = document.getElementById("namaInput").value.trim();
    const gulaMin = document.getElementById("gulaMinInput").value.trim();
    const gulaMax = document.getElementById("gulaMaxInput").value.trim();
    const deskripsi = document.getElementById("deskripsiInput").value.trim();

    if (!nama || !gulaMin || !gulaMax) {
        alert("Field Nama, Gula Minimal, dan Gula Maksimal wajib diisi!");
        return;
    }

    const data = {
        nama,
        gula_min: gulaMin,
        gula_max: gulaMax,
        deskripsi,
    };

    const url =
        currentMode === "add"
            ? "/api/kategori_gula"
            : `/api/kategori_gula/${currentEditId}`;
    const method = currentMode === "add" ? "POST" : "PUT";

    fetch(url, {
        method: method,
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    })
        .then(async (res) => {
            if (!res.ok) {
                const errText = await res.text();
                alert("Terjadi kesalahan pada server:\n" + errText);
                throw new Error(errText);
            }
            return res.json();
        })
        .then((data) => {
            alert(data.message || "Berhasil menyimpan data!");
            closeModal();
            updateTable();
        })
        .catch((err) => {
            console.error(err);
            alert("Gagal menyimpan data. Cek konsol untuk detail.");
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

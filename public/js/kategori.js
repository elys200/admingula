let currentMode = "add";
let currentEditId = null;
let deleteTargetId = null;

// Buka modal
function openModal(mode, id = null) {
    currentMode = mode;
    currentEditId = id;

    document.getElementById("modalTitle").textContent =
        mode === "add" ? "Tambah Kategori Gula Baru" : "Edit Kategori Gula";
    document.getElementById("saveButton").textContent =
        mode === "add" ? "Simpan" : "Update";

    if (mode === "add") {
        clearForm();
    }

    if (mode === "edit") {
        fetch(`/api/v1/kategori_gula/${id}`)
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
            });
    }

    document.getElementById("modalOverlay").classList.add("active");
}

// Tutup modal
function closeModal() {
    document.getElementById("modalOverlay").classList.remove("active");
    clearForm();
}

// Bersihkan form
function clearForm() {
    document.getElementById("namaInput").value = "";
    document.getElementById("gulaMinInput").value = "";
    document.getElementById("gulaMaxInput").value = "";
    document.getElementById("deskripsiInput").value = "";
}

// Simpan data
function saveData() {
    const nama = document
        .getElementById("namaInput")
        .value.trim()
        .toLowerCase();
    const gulaMin = document.getElementById("gulaMinInput").value.trim();
    const gulaMax = document.getElementById("gulaMaxInput").value.trim();
    const deskripsi = document.getElementById("deskripsiInput").value.trim();

    // Validasi nama
    const allowedNama = ["low", "normal", "high"];
    if (!allowedNama.includes(nama)) {
        alert("Nama harus salah satu dari: low, normal, atau high.");
        return;
    }

    // Validasi gula_min
    const minVal = parseFloat(gulaMin);
    if (isNaN(minVal) || minVal < 0 || minVal > 999999.99) {
        alert("Gula Minimal harus berupa angka antara 0 hingga 999999.99");
        return;
    }

    // Validasi gula_max (jika diisi)
    let maxVal = null;
    if (gulaMax) {
        maxVal = parseFloat(gulaMax);
        if (isNaN(maxVal)) {
            alert("Gula Maksimal harus berupa angka jika diisi.");
            return;
        }
        if (maxVal < minVal) {
            alert(
                "Gula Maksimal harus lebih besar atau sama dengan Gula Minimal."
            );
            return;
        }
    }

    // Validasi deskripsi panjang maksimal
    if (deskripsi.length > 255) {
        alert("Deskripsi tidak boleh lebih dari 255 karakter.");
        return;
    }

    const formData = new FormData();
    formData.append("nama", nama);
    formData.append("gula_min", gulaMin);
    formData.append("gula_max", gulaMax); // boleh kosong
    formData.append("deskripsi", deskripsi);

    let url = "/api/v1/kategori_gula";
    let method = "POST";
    if (currentMode === "edit") {
        url = `/api/v1/kategori_gula/${currentEditId}`;
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

// Event listener tombol konfirmasi dan batal hapus
document.getElementById("cancelDeleteBtn").addEventListener("click", () => {
    closeDeleteModal();
});
document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
    confirmDelete();
});

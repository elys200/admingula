let currentMode = "add";
let currentEditId = null;
let deleteTargetId = null;

// Fungsi untuk mengonversi textarea ke array
function convertTextToArray(text) {
    if (!text || text.trim() === "") return [];

    return text
        .split("\n")
        .map((line) => line.trim())
        .filter((line) => line !== ""); 
}

// Fungsi untuk mengonversi array ke string teks
function convertArrayToText(arr) {
    if (!Array.isArray(arr)) return "";
    return arr.join("\n");
}

// Modal Tambah / Edit
function openModal(mode, id = null, data = null) {
    currentMode = mode;
    currentEditId = id;

    document.getElementById("modalTitle").textContent =
        mode === "add" ? "Tambah Resep Baru" : "Edit Resep";
    document.getElementById("saveButton").textContent =
        mode === "add" ? "Simpan" : "Update";

    clearForm();

    if (mode === "edit" && data) {
        // Set basic data
        document.getElementById("namaInput").value = data.nama || "";
        document.getElementById("deskripsiInput").value = data.deskripsi || "";
        document.getElementById("kaloriInput").value = data.total_kalori || "";
        document.getElementById("karbohidratInput").value =
            data.total_karbohidrat || "";
        document.getElementById("lemakInput").value = data.total_lemak || "";
        document.getElementById("gulaInput").value = data.kadar_gula || "";

        // Fungsi helper untuk parse string JSON ke array dengan aman
        function safeParse(field) {
            if (!field) return [];
            if (Array.isArray(field)) return field;
            try {
                const parsed = JSON.parse(field);
                return Array.isArray(parsed) ? parsed : [];
            } catch {
                return [];
            }
        }

        // Ambil data detail dari API supaya data array benar-benar fresh dan valid
        fetch(`/api/resep/${id}`)
            .then((response) => response.json())
            .then((result) => {
                const resepData = result.data;

                // Set textarea dengan data parsed
                document.getElementById("panduanInput").value =
                    convertArrayToText(safeParse(resepData.panduan));
                document.getElementById("bahanInput").value =
                    convertArrayToText(safeParse(resepData.bahan));
                document.getElementById("tipsInput").value = convertArrayToText(
                    safeParse(resepData.tips)
                );
            })
            .catch((error) => {
                console.error("Error loading data:", error);

                // Fallback pakai data awal yang sudah diterima
                document.getElementById("panduanInput").value =
                    convertArrayToText(safeParse(data.panduan));
                document.getElementById("bahanInput").value =
                    convertArrayToText(safeParse(data.bahan));
                document.getElementById("tipsInput").value = convertArrayToText(
                    safeParse(data.tips)
                );
            });

        // Set preview gambar jika ada
        if (data.gambar) {
            const preview = document.getElementById("imagePreview");
            preview.src = `/storage/${data.gambar}`;
            preview.style.display = "block";
        }
    }

    document.getElementById("modalOverlay").classList.add("active");
}

function closeModal() {
    document.getElementById("modalOverlay").classList.remove("active");
}

function clearForm() {
    document.getElementById("namaInput").value = "";
    document.getElementById("deskripsiInput").value = "";
    document.getElementById("panduanInput").value = "";
    document.getElementById("tipsInput").value = "";
    document.getElementById("kaloriInput").value = "";
    document.getElementById("karbohidratInput").value = "";
    document.getElementById("lemakInput").value = "";
    document.getElementById("gulaInput").value = "";
    document.getElementById("bahanInput").value = "";
    document.getElementById("imagePreview").style.display = "none";
    document.getElementById("gambarInput").value = "";
}

// Tambah/Edit Resep
function saveData() {
    const formData = new FormData();

    // Validasi input required
    const nama = document.getElementById("namaInput").value.trim();
    if (!nama) {
        alert("Nama resep harus diisi!");
        return;
    }

    formData.append("nama", nama);
    formData.append(
        "deskripsi",
        document.getElementById("deskripsiInput").value
    );
    formData.append(
        "total_kalori",
        document.getElementById("kaloriInput").value
    );
    formData.append(
        "total_karbohidrat",
        document.getElementById("karbohidratInput").value
    );
    formData.append("total_lemak", document.getElementById("lemakInput").value);
    formData.append("kadar_gula", document.getElementById("gulaInput").value);

    // Mengubah input teks menjadi array
    const panduanText = document.getElementById("panduanInput").value;
    const panduanArray = convertTextToArray(panduanText);
    formData.append("panduan", JSON.stringify(panduanArray));

    const bahanText = document.getElementById("bahanInput").value;
    const bahanArray = convertTextToArray(bahanText);
    formData.append("bahan", JSON.stringify(bahanArray));

    const tipsText = document.getElementById("tipsInput").value;
    const tipsArray = convertTextToArray(tipsText);
    formData.append("tips", JSON.stringify(tipsArray));

    const gambar = document.getElementById("gambarInput").files[0];
    if (gambar) {
        formData.append("gambar", gambar);
    }

    // URL & Method
    const url =
        currentMode === "add" ? "/api/resep" : `/api/resep/${currentEditId}`;
    const method = "POST";

    if (currentMode === "edit") {
        formData.append("_method", "PUT");
    }

    // Menonaktifkan tombol simpan sementara
    const saveButton = document.getElementById("saveButton");
    saveButton.disabled = true;
    saveButton.textContent = "Menyimpan...";

    fetch(url, {
        method,
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then(async (res) => {
            if (!res.ok) {
                const errorText = await res.text();
                throw new Error(errorText);
            }
            return res.json();
        })
        .then((data) => {
            alert(data.message || "Berhasil disimpan.");
            closeModal();
            location.reload();
        })
        .catch((err) => {
            console.error("Error:", err);
            alert("Gagal menyimpan data: " + err.message);
        })
        .finally(() => {
            // Menyalakan kembali tombol simpan
            saveButton.disabled = false;
            saveButton.textContent =
                currentMode === "add" ? "Simpan" : "Update";
        });
}

// Hapus Resep
function openDeleteModal(id, nama) {
    deleteTargetId = id;
    document.getElementById("deleteTitle").textContent = `"${nama}"`;
    document.getElementById("deleteModalOverlay").style.display = "flex";
}

function closeDeleteModal() {
    deleteTargetId = null;
    document.getElementById("deleteModalOverlay").style.display = "none";
}

function confirmDelete() {
    if (!deleteTargetId) return;

    fetch(`/api/resep/${deleteTargetId}`, {
        method: "DELETE",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then(async (res) => {
            if (!res.ok) {
                const errorText = await res.text();
                throw new Error(errorText);
            }
            return res.json();
        })
        .then((data) => {
            alert(data.message || "Data berhasil dihapus!");
            closeDeleteModal();
            location.reload();
        })
        .catch((err) => {
            console.error("Error:", err);
            alert("Gagal menghapus data: " + err.message);
        });
}

// Gambar Preview
document.getElementById("gambarInput").addEventListener("change", function () {
    const file = this.files[0];
    const preview = document.getElementById("imagePreview");
    if (file) {
        // Validasi file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert("Ukuran file terlalu besar! Maksimal 5MB.");
            this.value = "";
            preview.style.display = "none";
            return;
        }

        // Validasi file type
        if (!file.type.startsWith("image/")) {
            alert("File harus berupa gambar!");
            this.value = "";
            preview.style.display = "none";
            return;
        }

        preview.src = URL.createObjectURL(file);
        preview.style.display = "block";

        // Menghapus URL object setelah preview dimuat
        preview.onload = function () {
            URL.revokeObjectURL(this.src);
        };
    } else {
        preview.src = "";
        preview.style.display = "none";
    }
});

// Event Listeners
document.addEventListener("DOMContentLoaded", function () {
    // Modal close events
    document
        .getElementById("cancelDeleteBtn")
        .addEventListener("click", closeDeleteModal);
    document
        .getElementById("confirmDeleteBtn")
        .addEventListener("click", confirmDelete);

    // Close modal when clicking overlay
    document
        .getElementById("modalOverlay")
        .addEventListener("click", function (e) {
            if (e.target === this) {
                closeModal();
            }
        });

    document
        .getElementById("deleteModalOverlay")
        .addEventListener("click", function (e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

    // Escape key to close modals
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            closeModal();
            closeDeleteModal();
        }
    });
});

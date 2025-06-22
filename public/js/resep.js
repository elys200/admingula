// Global variables
let currentMode = "add",
    currentEditId = null,
    deleteTargetId = null;
let currentPage = 1,
    currentSearchQuery = "",
    isLoading = false;

// Utility functions
const convertTextToArray = (text) =>
    text?.trim()
        ? text
            .split("\n")
            .map((l) => l.trim())
            .filter(Boolean)
        : [];
const convertArrayToText = (arr) => (Array.isArray(arr) ? arr.join("\n") : "");
const safeParse = (field) => {
    if (!field) return [];
    if (Array.isArray(field)) return field;
    try {
        return Array.isArray(JSON.parse(field)) ? JSON.parse(field) : [];
    } catch {
        return [];
    }
};

// Modal functions
function openModal(mode, id = null, data = null) {
    currentMode = mode;
    currentEditId = id;
    document.getElementById("modalTitle").textContent =
        mode === "add" ? "Tambah Resep Baru" : "Edit Resep";
    document.getElementById("saveButton").textContent =
        mode === "add" ? "Simpan" : "Update";
    clearForm();

    if (mode === "edit" && data) {
        // Fill basic fields
        [
            "nama",
            "deskripsi",
            "total_kalori",
            "total_karbohidrat",
            "total_lemak",
            "kadar_gula",
        ].forEach((field) => {
            const input = document.getElementById(
                field === "total_kalori"
                    ? "kaloriInput"
                    : field === "total_karbohidrat"
                    ? "karbohidratInput"
                    : field === "total_lemak"
                    ? "lemakInput"
                    : field === "kadar_gula"
                    ? "gulaInput"
                    : field + "Input"
            );
            if (input) input.value = data[field] || "";
        });

        // Fill array fields
        fetch(`/api/v1/resep/${id}`)
            .then((res) => res.json())
            .then((result) => {
                const r = result.data;
                document.getElementById("panduanInput").value =
                    convertArrayToText(safeParse(r.panduan));
                document.getElementById("bahanInput").value =
                    convertArrayToText(safeParse(r.bahan));
                document.getElementById("tipsInput").value = convertArrayToText(
                    safeParse(r.tips)
                );
            })
            .catch(() => {
                document.getElementById("panduanInput").value =
                    convertArrayToText(safeParse(data.panduan));
                document.getElementById("bahanInput").value =
                    convertArrayToText(safeParse(data.bahan));
                document.getElementById("tipsInput").value = convertArrayToText(
                    safeParse(data.tips)
                );
            });

        // Show image preview
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
    [
        "nama",
        "deskripsi",
        "panduan",
        "tips",
        "kalori",
        "karbohidrat",
        "lemak",
        "gula",
        "bahan",
    ].forEach((id) => {
        const element = document.getElementById(id + "Input");
        if (element) element.value = "";
    });

    const imagePreview = document.getElementById("imagePreview");
    const gambarInput = document.getElementById("gambarInput");
    if (imagePreview) imagePreview.style.display = "none";
    if (gambarInput) gambarInput.value = "";
}

function saveData() {
    const nama = document.getElementById("namaInput").value.trim();
    if (!nama) return alert("Nama resep harus diisi!");

    const formData = new FormData();
    const fields = {
        nama,
        deskripsi: document.getElementById("deskripsiInput").value,
        total_kalori: document.getElementById("kaloriInput").value,
        total_karbohidrat: document.getElementById("karbohidratInput").value,
        total_lemak: document.getElementById("lemakInput").value,
        kadar_gula: document.getElementById("gulaInput").value,
        panduan: JSON.stringify(
            convertTextToArray(document.getElementById("panduanInput").value)
        ),
        bahan: JSON.stringify(
            convertTextToArray(document.getElementById("bahanInput").value)
        ),
        tips: JSON.stringify(
            convertTextToArray(document.getElementById("tipsInput").value)
        ),
    };

    Object.entries(fields).forEach(([key, value]) =>
        formData.append(key, value)
    );

    const gambar = document.getElementById("gambarInput").files[0];
    if (gambar) formData.append("gambar", gambar);

    const url =
        currentMode === "add"
            ? "/api/v1/resep"
            : `/api/v1/resep/${currentEditId}`;
    if (currentMode === "edit") formData.append("_method", "PUT");

    const saveButton = document.getElementById("saveButton");
    saveButton.disabled = true;
    saveButton.textContent = "Menyimpan...";

    fetch(url, {
        method: "POST",
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" },
    })
        .then(async (res) => {
            if (!res.ok) throw new Error(await res.text());
            return res.json();
        })
        .then((data) => {
            alert(data.message || "Berhasil disimpan.");
            closeModal();
            loadResep(currentPage);
        })
        .catch((err) => alert("Gagal menyimpan data: " + err.message))
        .finally(() => {
            saveButton.disabled = false;
            saveButton.textContent =
                currentMode === "add" ? "Simpan" : "Update";
        });
}

// Delete functions
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

    fetch(`/api/v1/resep/${deleteTargetId}`, {
        method: "DELETE",
        headers: { "X-Requested-With": "XMLHttpRequest" },
    })
        .then(async (res) => {
            if (!res.ok) throw new Error(await res.text());
            return res.json();
        })
        .then((data) => {
            alert(data.message || "Data berhasil dihapus!");
            closeDeleteModal();
            loadResep(currentPage);
        })
        .catch((err) => alert("Gagal menghapus data: " + err.message));
}

// Load and display data
function loadResep(page = 1) {
    if (isLoading) return;

    isLoading = true;
    currentPage = page;

    const tableBody = document.querySelector("#table-body");
    if (tableBody)
        tableBody.innerHTML =
            '<tr><td colspan="12" style="text-align: center;">Loading...</td></tr>';

    const searchParam = currentSearchQuery
        ? `&search=${encodeURIComponent(currentSearchQuery)}`
        : "";

    fetch(`/api/v1/resep?page=${page}${searchParam}`)
        .then((res) => {
            if (!res.ok) throw new Error("Network response was not ok");
            return res.json();
        })
        .then((data) => {
            updateTableContent(data.data);
            renderPagination(data);
        })
        .catch((error) => {
            console.error("Error loading resep:", error);
            if (tableBody)
                tableBody.innerHTML =
                    '<tr><td colspan="12" style="text-align: center;">Error loading data</td></tr>';
        })
        .finally(() => {
            isLoading = false;
        });
}

function updateTableContent(data) {
    const tableBody = document.querySelector("#table-body");
    if (!tableBody) return;

    if (!data || data.length === 0) {
        tableBody.innerHTML =
            '<tr><td colspan="12" style="text-align: center;">Tidak ada data</td></tr>';
        return;
    }

    const processArrayData = (field) => {
        let fieldData = field;
        if (typeof fieldData === "string") {
            try {
                const parsed = JSON.parse(fieldData);
                fieldData = Array.isArray(parsed) ? parsed : [fieldData];
            } catch {
                fieldData = [fieldData];
            }
        } else if (!Array.isArray(fieldData)) {
            fieldData = fieldData ? [fieldData] : [];
        }
        return fieldData.filter((item) => item).join(", ");
    };

    tableBody.innerHTML = data
        .map((r) => {
            const panduanText = processArrayData(r.panduan);
            const bahanText = processArrayData(r.bahan);
            const tipsText = processArrayData(r.tips);
            const escapedData = JSON.stringify(r)
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#39;");
            const escapedNama = (r.nama || "")
                .replace(/'/g, "&#39;")
                .replace(/"/g, "&quot;");

            return `
            <tr>
                <td>${r.id}</td>
                <td><div class="tabel-truncate" title="${escapedNama}">${
                r.nama
            }</div></td>
                <td><div class="tabel-truncate" title="${(
                    r.deskripsi || ""
                ).replace(/"/g, "&quot;")}">${r.deskripsi || ""}</div></td>
                <td><div class="tabel-truncate" title="${panduanText.replace(
                    /"/g,
                    "&quot;"
                )}">${panduanText}</div></td>
                <td>${r.total_kalori || ""}</td>
                <td>${r.total_karbohidrat || ""}</td>
                <td>${r.total_lemak || ""}</td>
                <td>${r.kadar_gula || ""}</td>
                <td><div class="tabel-truncate" title="${bahanText.replace(
                    /"/g,
                    "&quot;"
                )}">${bahanText}</div></td>
                <td><div class="tabel-truncate" title="${tipsText.replace(
                    /"/g,
                    "&quot;"
                )}">${tipsText}</div></td>
                <td>${
                    r.gambar
                        ? `<img src="/storage/${r.gambar}" alt="Gambar" width="60" style="border-radius: 4px;">`
                        : "Tidak ada"
                }</td>
                <td>
                    <div class="icon-group">
                        <iconify-icon icon="uil:edit" width="24" style="color: #E9B310; cursor: pointer;" onclick='openModal("edit", ${
                            r.id
                        }, ${escapedData})'></iconify-icon>
                        <iconify-icon icon="heroicons:trash-16-solid" width="24" style="color: #E43A15; cursor: pointer;" onclick="openDeleteModal(${
                            r.id
                        }, '${escapedNama}')"></iconify-icon>
                    </div>
                </td>
            </tr>
        `;
        })
        .join("");
}

function renderPagination(data) {
    const container = document.getElementById("pagination");
    if (!container) return;

    const totalPages = data.last_page || 1;
    const currentPageNum = data.current_page || 1;

    let html = `<button class="page-button" ${
        currentPageNum <= 1 ? "disabled" : ""
    } onclick="loadResep(${currentPageNum - 1})">&lt;</button>`;

    const startPage = Math.max(1, currentPageNum - 2);
    const endPage = Math.min(totalPages, startPage + 4);

    for (let i = startPage; i <= endPage; i++) {
        html += `<button class="page-button ${
            i === currentPageNum ? "active" : ""
        }" onclick="loadResep(${i})">${i}</button>`;
    }

    html += `<button class="page-button" ${
        currentPageNum >= totalPages ? "disabled" : ""
    } onclick="loadResep(${currentPageNum + 1})">&gt;</button>`;

    container.innerHTML = html;
}

// Search functions
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
        loadResep(1);
    }
}

function handleSearch(input) {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        currentSearchQuery = input.value.trim();
        currentPage = 1;
        loadResep(1);
    }, 400);
}

// Initialize
document.addEventListener("DOMContentLoaded", function () {
    // Image preview handler
    const gambarInput = document.getElementById("gambarInput");
    if (gambarInput) {
        gambarInput.addEventListener("change", function () {
            const file = this.files[0];
            const preview = document.getElementById("imagePreview");
            if (file) {
                if (
                    file.size > 5 * 1024 * 1024 ||
                    !file.type.startsWith("image/")
                ) {
                    alert("File tidak valid!");
                    this.value = "";
                    if (preview) preview.style.display = "none";
                    return;
                }
                if (preview) {
                    preview.src = URL.createObjectURL(file);
                    preview.style.display = "block";
                    preview.onload = () => URL.revokeObjectURL(preview.src);
                }
            } else if (preview) {
                preview.src = "";
                preview.style.display = "none";
            }
        });
    }

    // Event listeners
    document
        .getElementById("cancelDeleteBtn")
        ?.addEventListener("click", closeDeleteModal);
    document
        .getElementById("confirmDeleteBtn")
        ?.addEventListener("click", confirmDelete);

    document.getElementById("modalOverlay")?.addEventListener("click", (e) => {
        if (e.target === e.currentTarget) closeModal();
    });

    document
        .getElementById("deleteModalOverlay")
        ?.addEventListener("click", (e) => {
            if (e.target === e.currentTarget) closeDeleteModal();
        });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeModal();
            closeDeleteModal();
        }
    });

    // Search setup
    const searchBox = document.querySelector(".search-box");
    if (searchBox) {
        let searchInput = searchBox.querySelector(".search-input");
        if (!searchInput) {
            searchInput = document.createElement("input");
            Object.assign(searchInput, {
                type: "text",
                placeholder: "Cari nama resep...",
                className: "search-input",
            });
            Object.assign(searchInput.style, {
                padding: "6px 10px",
                border: "1px solid #ccc",
                borderRadius: "5px",
                marginLeft: "10px",
            });
            searchBox.appendChild(searchInput);
        }

        searchInput.addEventListener("input", function () {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                currentSearchQuery = this.value.trim();
                currentPage = 1;
                loadResep(1);
            }, 400);
        });
    }

    loadResep(1);
});

// Expose to global scope
Object.assign(window, {
    openModal,
    closeModal,
    saveData,
    openDeleteModal,
    closeDeleteModal,
    confirmDelete,
    loadResep,
    toggleSearch,
    handleSearch,
});

let currentPage = 1;
let currentSearchQuery = "";
let isLoading = false;

document.addEventListener("DOMContentLoaded", () => {
    loadJurnal(1);
});

async function loadJurnal(page = 1) {
    if (isLoading) return;
    isLoading = true;
    currentPage = page;

    const tableBody = document.querySelector("#table-body");
    tableBody.innerHTML = `
        <tr>
            <td colspan="5" style="text-align:center">Loading...</td>
        </tr>`;

    const searchParam = currentSearchQuery
        ? `&search=${encodeURIComponent(currentSearchQuery)}`
        : "";

    try {
        const res = await fetch(`/api/v1/jurnal?page=${page}${searchParam}`);
        if (!res.ok) throw new Error(`Failed to load data: ${res.status}`);

        const json = await res.json();

        const from = json?.pagination?.from ?? 0;
        updateTable(json.data, from);
        renderPagination(json.pagination);
    } catch (err) {
        console.error("Error:", err);
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align:center; color: red;">Error loading data</td>
            </tr>`;
    } finally {
        isLoading = false;
    }
}

function updateTable(data, from) {
    const tableBody = document.querySelector("#table-body");
    if (!data || data.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align:center">Tidak ada data</td>
            </tr>`;
        return;
    }

    tableBody.innerHTML = data
        .map(
            (item, idx) => `
        <tr>
            <td>${from + idx}</td>
            <td>
                <div class="tabel-truncate" title="${escapeHtml(
                    item.user?.username || "-"
                )}">
                    ${escapeHtml(item.user?.username || "-")}
                </div>
            </td>
            <td>${escapeHtml(item.total_gula?.toString() || "0")} gram</td>
            <td>${formatDate(item.date)}</td>
            <td>
                <div class="tabel-truncate" title="${escapeHtml(
                    item.kategori?.nama || "-"
                )}">
                    ${escapeHtml(item.kategori?.nama || "-")}
                </div>
            </td>
        </tr>
    `
        )
        .join("");
}

function renderPagination(pagination) {
    const container = document.getElementById("pagination");
    if (!container) return;

    const totalPages = pagination.last_page || 1;
    const currentPageNum = pagination.current_page || 1;
    let html = `<button class="page-button" ${
        currentPageNum <= 1 ? "disabled" : ""
    } onclick="loadJurnal(${currentPageNum - 1})">&lt;</button>`;

    const startPage = Math.max(1, currentPageNum - 2);
    const endPage = Math.min(totalPages, startPage + 4);

    for (let i = startPage; i <= endPage; i++) {
        html += `<button class="page-button ${
            i === currentPageNum ? "active" : ""
        }"
            onclick="loadJurnal(${i})">${i}</button>`;
    }

    html += `<button class="page-button" ${
        currentPageNum >= totalPages ? "disabled" : ""
    } onclick="loadJurnal(${currentPageNum + 1})">&gt;</button>`;

    container.innerHTML = html;
}

function handleSearch(input) {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        currentSearchQuery = input.value.trim();
        loadJurnal(1);
    }, 400);
}

function toggleSearch() {
    const wrapper = document.querySelector(".search-wrapper");
    wrapper.classList.toggle("active");
    const input = document.getElementById("searchInput");
    if (wrapper.classList.contains("active")) {
        input.focus();
    } else {
        input.value = "";
        currentSearchQuery = "";
        loadJurnal(1);
    }
}

function formatDate(dateString) {
    if (!dateString) return "-";
    const d = new Date(dateString);
    if (isNaN(d)) return "-";
    return d.toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "long",
        year: "numeric",
    });
}

function escapeHtml(str) {
    return (
        str
            ?.replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;") || ""
    );
}

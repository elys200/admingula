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
    tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center">Loading...</td></tr>`;

    const searchParam = currentSearchQuery
        ? `&search=${encodeURIComponent(currentSearchQuery)}`
        : "";

    try {
        const res = await fetch(
            `/admin/jurnal/data?page=${page}${searchParam}`
        );
        if (!res.ok) throw new Error("Failed to load data");
        const data = await res.json();

        updateTable(data.data);
        renderPagination(data);
    } catch (err) {
        console.error(err);
        tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center">Error loading data</td></tr>`;
    } finally {
        isLoading = false;
    }
}

function updateTable(data) {
    const tableBody = document.querySelector("#table-body");
    if (!data.length) {
        tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center">Tidak ada data</td></tr>`;
        return;
    }

    tableBody.innerHTML = data
        .map(
            (item) => `
        <tr>
            <td>${item.id}</td>
            <td>${escapeHtml(item.user?.username || "-")}</td>
            <td>${item.total_gula} gram</td>
            <td>${formatDate(item.date)}</td>
            <td>${escapeHtml(item.kategori?.nama || "-")}</td>
        </tr>
    `
        )
        .join("");
}

function renderPagination(data) {
    const container = document.getElementById("pagination");
    const { current_page, last_page } = data;
    let html = `<button ${
        current_page <= 1 ? "disabled" : ""
    } onclick="loadJurnal(${current_page - 1})">&lt;</button>`;

    const startPage = Math.max(1, current_page - 2);
    const endPage = Math.min(last_page, startPage + 4);

    for (let i = startPage; i <= endPage; i++) {
        html += `<button class="${
            i === current_page ? "active" : ""
        }" onclick="loadJurnal(${i})">${i}</button>`;
    }

    html += `<button ${
        current_page >= last_page ? "disabled" : ""
    } onclick="loadJurnal(${current_page + 1})">&gt;</button>`;
    container.innerHTML = html;
}

function handleSearch(input) {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        currentSearchQuery = input.value.trim();
        currentPage = 1;
        loadJurnal(1);
    }, 300);
}

function toggleSearch() {
    const wrapper = document.querySelector(".search-wrapper");
    const input = document.getElementById("searchInput");

    wrapper.classList.toggle("active");
    if (wrapper.classList.contains("active")) {
        setTimeout(() => input.focus(), 100);
    } else {
        input.value = "";
        currentSearchQuery = "";
        loadJurnal(1);
    }
}

function formatDate(dateString) {
    if (!dateString) return "-";
    const options = { day: "2-digit", month: "long", year: "numeric" };
    return new Date(dateString).toLocaleDateString("id-ID", options);
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

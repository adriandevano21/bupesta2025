document.addEventListener('DOMContentLoaded', function () {
    // LIVE SEARCH TABEL LOG
    const searchInput = document.getElementById('pencarianAktivitas');
    const tableRows = document.querySelectorAll('#tabelAktivitas tbody tr');

    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const keyword = e.target.value.toLowerCase();
            tableRows.forEach(row => {
                if (row.querySelector('td[colspan]')) return;
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    }
});
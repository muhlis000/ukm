/**
 * File: search.js
 * Deskripsi: Mengelola fungsionalitas live search dengan struktur HTML dan kelas yang benar.
 */
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchResultsContainer = document.getElementById('search-results');

    if (!searchInput || !searchResultsContainer) {
        return;
    }

    const debounce = (func, delay) => {
        let timeoutId;
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                func.apply(this, args);
            }, delay);
        };
    };

    const performSearch = async (query) => {
        if (query.length < 2) {
            searchResultsContainer.style.display = 'none';
            searchResultsContainer.innerHTML = '';
            return;
        }
        try {
            const response = await fetch(`/api/search.php?q=${encodeURIComponent(query)}`);
            if (!response.ok) throw new Error('Network response was not ok');
            const results = await response.json();
            displayResults(results);
        } catch (error) {
            console.error('Search error:', error);
            searchResultsContainer.innerHTML = `<div class="search-result-item-error">Gagal memuat hasil.</div>`;
            searchResultsContainer.style.display = 'block';
        }
    };
    
    const displayResults = (results) => {
        searchResultsContainer.innerHTML = '';
        if (results.length === 0) {
            searchResultsContainer.innerHTML = `<div class="search-result-item-error">Tidak ada UKM yang ditemukan.</div>`;
        } else {
            results.forEach(ukm => {
                const item = document.createElement('a');
                item.href = `/ukm/detail.php?id=${ukm.id_ukm}`;
                item.className = 'search-result-item';

                item.innerHTML = `
                    <img src="/upload/logo/${ukm.logo}" alt="Logo ${ukm.nama_ukm}" onerror="this.onerror=null;this.src='https://placehold.co/40x40/e0e7ff/4f46e5?text=${ukm.nama_ukm.charAt(0)}';">
                    <div class="search-result-info">
                        <strong>${ukm.nama_ukm}</strong>
                        <span>${ukm.nama_kategori}</span>
                    </div>
                `;
                searchResultsContainer.appendChild(item);
            });
        }
        searchResultsContainer.style.display = 'block';
    };

    searchInput.addEventListener('input', debounce((event) => {
        performSearch(event.target.value);
    }, 300));
    
    document.addEventListener('click', (event) => {
        const searchContainer = document.querySelector('.search-container');
        if (searchContainer && !searchContainer.contains(event.target)) {
            searchResultsContainer.style.display = 'none';
        }
    });
});

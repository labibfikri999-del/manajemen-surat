{{-- resources/views/partials/scripts.blade.php --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main');
        const btnCollapse = document.getElementById('btnCollapse');
        const btnOpenMobile = document.getElementById('btnOpenMobile');
        const mobileOverlay = document.getElementById('mobileOverlay');

        let collapsed = false;

        // Load saved state
        try {
            const saved = localStorage.getItem('sidebar.collapsed');
            if (saved === 'true') {
                collapsed = true;
                sidebar.classList.add('sidebar-collapsed');
                document.body.classList.add('sidebar-collapsed-state');
                if (main) {
                    main.classList.remove('main-with-sidebar');
                    main.classList.add('main-with-sidebar-collapsed');
                }
            }
        } catch(e){ }

        // Toggle collapse on desktop
        if (btnCollapse) {
            btnCollapse.addEventListener('click', function() {
                collapsed = !collapsed;
                sidebar.classList.toggle('sidebar-collapsed');
                document.body.classList.toggle('sidebar-collapsed-state');
                if (main) {
                    main.classList.toggle('main-with-sidebar-collapsed');
                    main.classList.toggle('main-with-sidebar');
                }
                
                try { localStorage.setItem('sidebar.collapsed', collapsed ? 'true' : 'false'); } catch(e){}
            });
        }

        // Open sidebar on mobile
        function openMobile() {
            sidebar.classList.remove('sidebar-hidden-mobile');
            mobileOverlay.classList.remove('hidden');
        }
        
        function closeMobile() {
            sidebar.classList.add('sidebar-hidden-mobile');
            mobileOverlay.classList.add('hidden');
        }

        if (btnOpenMobile) btnOpenMobile.addEventListener('click', openMobile);
        if (mobileOverlay) mobileOverlay.addEventListener('click', closeMobile);

        // Close on nav click (mobile)
        sidebar.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', function() {
                if (window.innerWidth < 768) closeMobile();
            });
        });

        // Resize handler
        function onResize() {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('sidebar-hidden-mobile');
                mobileOverlay.classList.add('hidden');
            } else {
                sidebar.classList.add('sidebar-hidden-mobile');
            }
        }
        window.addEventListener('resize', onResize);
        onResize();
    });

    // Notifikasi Balasan
    const btn = document.getElementById('btnBalasanNotif');
    const dropdown = document.getElementById('balasanNotifDropdown');
    if (btn && dropdown) {
        async function fetchBalasanCount() {
            const res = await fetch('/api/balasan/unread-count');
            const data = await res.json();
            const badge = document.getElementById('balasanNotifBadge');
            badge.textContent = data.count;
            badge.style.display = data.count > 0 ? 'inline-block' : 'none';
        }
        async function fetchBalasanList() {
            const res = await fetch('/api/balasan/unread-list');
            const data = await res.json();
            const list = document.getElementById('balasanNotifList');
            list.innerHTML = '';
            if (!data.dokumens || data.dokumens.length === 0) {
                list.innerHTML = '<div class="p-3 text-gray-500 text-sm">Tidak ada file balasan baru.</div>';
                return;
            }
            data.dokumens.forEach(dok => {
                if (!dok.balasan_file) return;
                const storageRoot = "{{ asset('storage') }}";
                const item = document.createElement('div');
                item.className = 'flex items-center justify-between px-3 py-2 border-b hover:bg-emerald-50 cursor-pointer';
                item.innerHTML = `<div>
                    <div class='font-semibold text-emerald-700'>${dok.judul}</div>
                    <div class='text-xs text-gray-500'>${dok.nomor_dokumen}</div>
                </div>
                <div class='flex gap-2'>
                    <button class='px-3 py-1 bg-emerald-600 text-white rounded text-xs font-bold hover:bg-emerald-700' onclick='markBalasanRead(${dok.id}, "${storageRoot}/${dok.balasan_file}")'>Lihat</button>
                    <a href='/api/dokumen/${dok.id}/download-balasan' class='px-3 py-1 bg-gray-200 text-emerald-700 rounded text-xs font-bold hover:bg-gray-300' download>Download</a>
                </div>`;
                list.appendChild(item);
            });
        }
        window.markBalasanRead = async function (dokumenId, fileUrl) {
            await fetch(`/api/balasan/mark-read/${dokumenId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            await fetchBalasanCount();
            await fetchBalasanList();
            window.open(fileUrl, '_blank');
        }
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
            if (!dropdown.classList.contains('hidden')) {
                fetchBalasanList();
            }
        });
        document.addEventListener('click', function(e) {
            if (!dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });
        setInterval(fetchBalasanCount, 5000);
        fetchBalasanCount();
    }

    // Global Preview Modal Logic (reused from Header)
    window.showDocumentPreview = function(url, title, extension = 'pdf') {
        const modal = document.getElementById('headerPreviewModal');
        const frame = document.getElementById('headerPreviewFrame');
        const titleEl = document.getElementById('headerPreviewTitle');
        const loading = document.getElementById('headerPreviewLoading');
        const error = document.getElementById('headerPreviewError');
        const errorIcon = error.querySelector('div.bg-amber-100'); // Container icon
        const errorTitle = error.querySelector('h4');
        const errorText = error.querySelector('p');
        const downloadBtn = document.getElementById('headerDownloadBtn');
        const downloadFallback = document.getElementById('headerDownloadFallback');

        if (!modal) return;

        // Hide Navbar for immersive view
        const navbar = document.querySelector('header');
        if (navbar) navbar.style.display = 'none';

        titleEl.textContent = title;
        loading.classList.remove('hidden');
        error.classList.add('hidden');
        
        // Reset src
        frame.src = url;
        
        // Update download links
        const downloadUrl = url.replace('/preview', '/download');
        downloadBtn.href = downloadUrl;
        downloadFallback.href = downloadUrl;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        const previewable = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'txt'];
        const office = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
        const ext = extension.toLowerCase();

        // Check format
        if (!previewable.includes(ext)) {
            loading.classList.add('hidden');
            error.classList.remove('hidden');
            
            if (office.includes(ext)) {
                // Friendly UI for Office Files
                if(errorIcon) errorIcon.className = 'bg-blue-100 text-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4';
                if(errorIcon) errorIcon.innerHTML = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';
                errorTitle.textContent = 'Preview tidak tersedia untuk format ini';
                errorText.textContent = 'File ini adalah dokumen Office (Word/Excel). Silakan download untuk membukanya.';
            } else {
                // Generic Error
                if(errorIcon) errorIcon.className = 'bg-amber-100 text-amber-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4';
                if(errorIcon) errorIcon.innerHTML = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
                errorTitle.textContent = 'Tidak dapat menampilkan preview';
                errorText.textContent = 'Format file ini mungkin tidak didukung untuk preview langsung oleh browser anda.';
            }
        }
    }

    window.closeDocumentPreview = function() {
        const modal = document.getElementById('headerPreviewModal');
        const frame = document.getElementById('headerPreviewFrame');
        
        // Show Navbar
        const navbar = document.querySelector('header');
        if (navbar) navbar.style.display = '';

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        frame.src = ''; 
    }

    // Close modal on click outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('headerPreviewModal');
        if (modal && e.target === modal) closeDocumentPreview();
    });
</script>

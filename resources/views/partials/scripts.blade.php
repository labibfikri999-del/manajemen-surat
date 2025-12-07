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
                const item = document.createElement('div');
                item.className = 'flex items-center justify-between px-3 py-2 border-b hover:bg-emerald-50 cursor-pointer';
                item.innerHTML = `<div>
                    <div class='font-semibold text-emerald-700'>${dok.judul}</div>
                    <div class='text-xs text-gray-500'>${dok.nomor_dokumen}</div>
                </div>
                <div class='flex gap-2'>
                    <button class='px-3 py-1 bg-emerald-600 text-white rounded text-xs font-bold hover:bg-emerald-700' onclick='markBalasanRead(${dok.id}, "/storage/${dok.balasan_file}")'>Lihat</button>
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
</script>

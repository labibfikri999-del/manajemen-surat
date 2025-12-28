{{-- resources/views/partials/header.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
@endphp
<header class="site-header bg-white border-b border-emerald-100 flex items-center justify-between px-4 md:px-6 lg:px-8 shadow-sm">
    <div class="flex items-center gap-4">
        <button id="btnOpenMobile" class="md:hidden text-emerald-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="flex items-center gap-3">
            <img src="/images/logo_rsi_ntb.png" alt="Yayasan Bersih Logo" class="w-10 h-10 object-contain" onerror="this.style.display='none'">
            <div class="flex flex-col">
                <span class="text-lg font-bold text-emerald-700">YARSI NTB</span>
                <span class="text-xs text-emerald-600">Sistem Arsip Digital</span>
            </div>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <span class="hidden sm:inline-block px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">
            {{ $user->role_label ?? ucfirst($role) }}
        </span>
        <div class="relative">
            <button id="btnBalasanNotif" class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-sm focus:outline-none" title="Notifikasi Balasan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span id="balasanNotifBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 font-bold" style="display:none;">0</span>
            </button>
            <div id="balasanNotifDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                <div class="p-3 border-b font-semibold text-gray-700">File Balasan Baru</div>
                <div id="balasanNotifList" class="max-h-64 overflow-y-auto"></div>
                <div class="p-2 text-xs text-gray-500 border-t">Klik untuk lihat/download file balasan</div>
            </div>
        </div>
        <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
        </div>
    </div>
</header>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Fetch unread balasan count
    async function fetchBalasanCount() {
        try {
            const res = await fetch('/api/balasan/unread-count');
            const data = await res.json();
            const badge = document.getElementById('balasanNotifBadge');
            if(badge) {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? 'inline-block' : 'none';
            }
        } catch (e) { console.error('Error fetching count', e); }
    }
    // Fetch unread balasan list
    async function fetchBalasanList() {
        try {
            const res = await fetch('/api/balasan/unread-list');
            const data = await res.json();
            const list = document.getElementById('balasanNotifList');
            list.innerHTML = '';
            if (data.dokumens.length === 0) {
                list.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Tidak ada file balasan baru.</div>';
                return;
            }
            data.dokumens.forEach(dok => {
                const ext = dok.balasan_file.split('.').pop().toLowerCase();
                // Escape quotes for JS string
                const safeTitle = dok.judul.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                const fileUrl = `/storage/${dok.balasan_file}`;
                
                const item = document.createElement('div');
                item.className = 'flex items-start gap-3 px-4 py-3 border-b hover:bg-emerald-50 transition-colors bg-white cursor-pointer group';
                
                // Click title/row to preview
                item.onclick = function(e) {
                    // Prevent if clicked on download link specific
                    if (e.target.closest('a[download]')) return;
                    showNotificationPreview(fileUrl, safeTitle, ext);
                    markBalasanRead(dok.id);
                };

                item.innerHTML = `
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-900 leading-snug mb-0.5 line-clamp-2 group-hover:text-emerald-700 transition-colors" title="${dok.judul}">
                        ${dok.judul}
                    </div>
                    <div class="text-xs text-gray-500 font-mono">${dok.nomor_dokumen || '-'}</div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0 self-center">
                    <button type="button" class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-semibold rounded-lg hover:bg-emerald-700 transition shadow-sm">
                        Lihat
                    </button>
                    <a href="${fileUrl}" download onclick="event.stopPropagation(); markBalasanRead(${dok.id})"
                        class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-200 transition border border-gray-200">
                        Download
                    </a>
                </div>`;
                list.appendChild(item);
            });
        } catch (e) {
            console.error('Error fetching list', e); 
            const list = document.getElementById('balasanNotifList');
            list.innerHTML = '<div class="p-3 text-red-500 text-sm">Gagal memuat data.</div>';
        }
    }
    // Mark balasan as read
    window.markBalasanRead = async function (dokumenId) {
        try {
            await fetch(`/api/balasan/mark-read/${dokumenId}`, {method:'POST'});
            fetchBalasanCount();
            // Don't refresh list immediately to keep item visible while downloading/viewing
        } catch (e) { console.error(e); }
    }
    // Dropdown logic
    const btn = document.getElementById('btnBalasanNotif');
    const dropdown = document.getElementById('balasanNotifDropdown');
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
        if (!dropdown.classList.contains('hidden')) {
            fetchBalasanList();
        }
    });
    document.addEventListener('click', function(e) {
        if (!dropdown.classList.contains('hidden') && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
    // Polling badge
    setInterval(fetchBalasanCount, 10000); // 10s polling
    fetchBalasanCount();
});

// Global functions for Header Preview Modal
function showNotificationPreview(url, title, extension) {
    const modal = document.getElementById('headerPreviewModal');
    const frame = document.getElementById('headerPreviewFrame');
    const titleEl = document.getElementById('headerPreviewTitle');
    const loading = document.getElementById('headerPreviewLoading');
    const error = document.getElementById('headerPreviewError');
    const downloadBtn = document.getElementById('headerDownloadBtn');
    const downloadFallback = document.getElementById('headerDownloadFallback');

    // Hide Navbar for immersive view (matching hasil-validasi behavior)
    const navbar = document.querySelector('header');
    if (navbar) navbar.style.display = 'none';

    titleEl.textContent = title;
    loading.classList.remove('hidden');
    error.classList.add('hidden');
    frame.src = url;
    
    downloadBtn.href = url;
    downloadFallback.href = url;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    const previewable = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'txt'];
    if (!previewable.includes(extension.toLowerCase())) {
        loading.classList.add('hidden');
        error.classList.remove('hidden');
    }
}

function closeNotificationPreview() {
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
    if (e.target === modal) closeNotificationPreview();
});
</script>
@endpush

{{-- Modal Preview Global (Header) --}}
<div id="headerPreviewModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-[100] p-4 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col mx-auto transform transition-all scale-100">
        <div class="p-4 border-b flex items-center justify-between bg-gray-50 rounded-t-2xl">
            <div>
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span id="headerPreviewTitle">Preview Dokumen</span>
                </h3>
            </div>
            <div class="flex items-center gap-2">
                <a id="headerDownloadBtn" href="#" target="_blank" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-white rounded-lg transition" title="Download / Buka di Tab Baru">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <button onclick="closeNotificationPreview()" class="p-2 text-gray-500 hover:text-red-600 hover:bg-white rounded-lg transition" title="Tutup">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div class="flex-1 bg-gray-100 relative p-0 overflow-hidden rounded-b-2xl">
            <div id="headerPreviewLoading" class="absolute inset-0 flex items-center justify-center bg-white z-10">
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <p class="text-sm text-gray-500 font-medium">Memuat dokumen...</p>
                </div>
            </div>
            <iframe id="headerPreviewFrame" class="w-full h-full border-0" onload="document.getElementById('headerPreviewLoading').classList.add('hidden')"></iframe>
            <div id="headerPreviewError" class="absolute inset-0 flex items-center justify-center bg-white hidden z-20">
                <div class="text-center p-8 max-w-md">
                    <div class="bg-amber-100 text-amber-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Tidak dapat menampilkan preview</h4>
                    <p class="text-gray-600 mb-6">Format file ini mungkin tidak didukung untuk preview langsung oleh browser anda.</p>
                    <a id="headerDownloadFallback" href="#" target="_blank" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download File
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

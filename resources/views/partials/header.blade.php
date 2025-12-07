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
            <img src="/images/Logo Yayasan Bersih.png" alt="Yayasan Bersih Logo" class="w-10 h-10 object-contain" onerror="this.style.display='none'">
            <div class="flex flex-col">
                <span class="text-lg font-bold text-emerald-700">YARSI NTB</span>
                <span class="text-xs text-emerald-600">Sistem Arsip Digital</span>
            </div>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <span class="hidden sm:inline-block px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">
            {{ ucfirst($role) }}
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
        const res = await fetch('/api/balasan/unread-count');
        const data = await res.json();
        const badge = document.getElementById('balasanNotifBadge');
        badge.textContent = data.count;
        badge.style.display = data.count > 0 ? 'inline-block' : 'none';
    }
    // Fetch unread balasan list
    async function fetchBalasanList() {
        const res = await fetch('/api/balasan/unread-list');
        const data = await res.json();
        const list = document.getElementById('balasanNotifList');
        list.innerHTML = '';
        if (data.dokumens.length === 0) {
            list.innerHTML = '<div class="p-3 text-gray-500 text-sm">Tidak ada file balasan baru.</div>';
            return;
        }
        data.dokumens.forEach(dok => {
            const item = document.createElement('div');
            item.className = 'flex items-center justify-between px-3 py-2 border-b hover:bg-emerald-50 cursor-pointer';
            item.innerHTML = `<div>
                <div class='font-semibold text-emerald-700'>${dok.judul}</div>
                <div class='text-xs text-gray-500'>${dok.nomor_dokumen}</div>
            </div>
            <a href='/storage/${dok.balasan_file}' target='_blank' class='px-3 py-1 bg-emerald-600 text-white rounded text-xs font-bold hover:bg-emerald-700' onclick='markBalasanRead(${dok.id})'>Lihat</a>`;
            list.appendChild(item);
        });
    }
    // Mark balasan as read
    window.markBalasanRead = async function (dokumenId) {
        await fetch(`/api/balasan/mark-read/${dokumenId}`, {method:'POST'});
        fetchBalasanCount();
        fetchBalasanList();
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
        if (!dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    });
    // Polling badge
    setInterval(fetchBalasanCount, 5000);
    fetchBalasanCount();
});
</script>
@endpush

{{-- resources/views/partials/styles.blade.php --}}
<style>
    :root{
        --header-h: 64px;
        --sidebar-w: 16rem;
        --sidebar-collapsed-w: 4.5rem;
    }
    html,body,#app { height: 100%; }
    .transition-smooth { transition: all .22s cubic-bezier(.2,.8,.2,1); }
    html, body { overflow-x: hidden; }
    .site-header { height: var(--header-h); position: sticky; top: 0; z-index: 100; }
    .sidebar {
        width: var(--sidebar-w);
        min-width: var(--sidebar-w);
        transition: width .25s ease, transform .25s ease;
        position: fixed;
        top: var(--header-h);
        left: 0;
        bottom: 0;
        z-index: 50;
        overflow-y: auto;
        background: white;
    }
    .sidebar-collapsed { width: var(--sidebar-collapsed-w) !important; min-width: var(--sidebar-collapsed-w) !important; }
    .sidebar.sidebar-collapsed .nav-label,
    .sidebar.sidebar-collapsed .sidebar-brand-text,
    .sidebar.sidebar-collapsed .role-badge-text { display: none !important; }
    .sidebar.sidebar-collapsed .nav-item { justify-content: center; }
    .sidebar.sidebar-collapsed .nav-item-locked { justify-content: center; }
    .sidebar.sidebar-collapsed .nav-item-locked .lock-icon { display: none; }
    .sidebar.sidebar-collapsed .role-badge { padding: 0.5rem; justify-content: center; }
    @media (max-width: 767.98px) { 
        .sidebar { transform: translateX(-100%); z-index: 60; }
        .sidebar-hidden-mobile { transform: translateX(-100%) !important; }
        .sidebar:not(.sidebar-hidden-mobile) { transform: translateX(0) !important; }
        .sidebar { width: var(--sidebar-w) !important; min-width: var(--sidebar-w) !important; } 
    }
    @media (min-width: 768px) { .sidebar { transform: translateX(0) !important; } }
    .main-with-sidebar { margin-left: var(--sidebar-w); transition: margin-left .25s ease; }
    .main-with-sidebar-collapsed { margin-left: var(--sidebar-collapsed-w); }
    @media (max-width: 767.98px) { 
        .main-with-sidebar { margin-left: 0 !important; }
        .main-with-sidebar-collapsed { margin-left: 0 !important; }
    }
    .mobile-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,.5); z-index: 55; }
    .nav-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.875rem; border-radius: 0.5rem; transition: all 0.2s; color: #065f46; }
    .nav-item.active { background: #d1fae5; color: #047857; border-left: 3px solid #047857; font-weight: 600; }
    .nav-item:hover:not(.active) { background: #ecfdf5; transform: translateX(2px); }
    .nav-item-locked { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.875rem; border-radius: 0.5rem; background: #f9fafb; cursor: not-allowed; opacity: 0.6; }
    .nav-item-locked:hover { background: #f3f4f6; }
    .tooltip { position: relative; }
    .tooltip-text { 
        position: absolute; 
        left: calc(100% + 10px); 
        top: 50%; 
        transform: translateY(-50%); 
        background: #065f46; 
        color: white; 
        padding: 0.5rem 0.75rem; 
        border-radius: 0.375rem; 
        white-space: nowrap; 
        opacity: 0; 
        pointer-events: none; 
        transition: opacity 0.3s ease; 
        font-size: 0.8125rem;
        font-weight: 600;
        z-index: 1000;
    }
    .tooltip-text::before {
        content: '';
        position: absolute;
        right: 100%;
        top: 50%;
        transform: translateY(-50%);
        border: 5px solid transparent;
        border-right-color: #065f46;
    }
    .tooltip-text.locked { background: #6b7280; }
    .tooltip-text.locked::before { border-right-color: #6b7280; }
    .sidebar.sidebar-collapsed .nav-item:hover .tooltip-text,
    .sidebar.sidebar-collapsed .nav-item-locked:hover .tooltip-text { opacity: 1; }
    
    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.3s ease-out; }
</style>

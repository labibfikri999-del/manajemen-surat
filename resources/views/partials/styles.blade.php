{{-- resources/views/partials/styles.blade.php --}}
<style>
    :root{
        --header-h: 64px;
        --sidebar-w: 16rem;
        --sidebar-collapsed-w: 4.5rem;
        --primary: #10b981;
        --primary-dark: #059669;
        --primary-light: #d1fae5;
        --primary-lighter: #ecfdf5;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html,body,#app { height: 100%; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
    .transition-smooth { transition: all .22s cubic-bezier(.2,.8,.2,1); }
    html, body { overflow: hidden; /* Prevent body scroll - fix for mobile jitter */ background: #f8fafc; overscroll-behavior: none; }
    .site-header { height: var(--header-h); flex-shrink: 0; /* Prevent shrink */ position: sticky; top: 0; z-index: 100; backdrop-filter: blur(12px); }
    .sidebar {
        width: var(--sidebar-w);
        min-width: var(--sidebar-w);
        transition: width .3s cubic-bezier(0.4, 0, 0.2, 1), transform .3s cubic-bezier(0.4, 0, 0.2, 1);
        position: fixed;
        top: var(--header-h);
        left: 0;
        bottom: 0;
        z-index: 50;
        overflow-y: auto;
        overflow-x: hidden;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-right: 1px solid #e2e8f0;
        box-shadow: 4px 0 24px rgba(0,0,0,0.02);
    }
    .sidebar::-webkit-scrollbar { width: 4px; }
    .sidebar::-webkit-scrollbar-track { background: transparent; }
    .sidebar::-webkit-scrollbar-thumb { background: #d1fae5; border-radius: 4px; }
    .sidebar::-webkit-scrollbar-thumb:hover { background: #a7f3d0; }
    
    /* Hide all horizontal scrollbars in sidebar */
    .sidebar, .sidebar * {
        scrollbar-width: thin;
        scrollbar-color: #d1fae5 transparent;
    }
    .sidebar ::-webkit-scrollbar { width: 4px; height: 0; }
    .sidebar ::-webkit-scrollbar-track { background: transparent; }
    .sidebar ::-webkit-scrollbar-thumb { background: #d1fae5; border-radius: 4px; }
    
    /* Hide horizontal scrollbar completely */
    .sidebar nav { overflow-x: hidden; }
    .sidebar nav::-webkit-scrollbar { height: 0; width: 4px; }
    .sidebar-collapsed { width: var(--sidebar-collapsed-w) !important; min-width: var(--sidebar-collapsed-w) !important; }
    .sidebar.sidebar-collapsed .nav-label,
    .sidebar.sidebar-collapsed .sidebar-brand-text,
    .sidebar.sidebar-collapsed .role-badge-text { display: none !important; }
    .sidebar.sidebar-collapsed .nav-item { justify-content: center; padding: 0.75rem; }
    .sidebar.sidebar-collapsed .nav-item-locked { justify-content: center; padding: 0.75rem; }
    .sidebar.sidebar-collapsed .nav-item-locked .lock-icon { display: none; }
    
    /* Profile card when collapsed */
    .sidebar.sidebar-collapsed .role-badge { 
        padding: 0.75rem; 
        justify-content: center;
        margin-bottom: 1rem;
    }
    .sidebar.sidebar-collapsed .role-badge > div { 
        justify-content: center; 
    }
    .sidebar.sidebar-collapsed .role-badge .w-12 { 
        width: 2.5rem; 
        height: 2.5rem; 
        font-size: 0.875rem;
    }
    
    /* Floating collapse button outside sidebar */
    .btn-collapse-outer {
        position: fixed;
        top: calc(var(--header-h) + 1.25rem);
        left: calc(var(--sidebar-w) - 16px);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: white;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        z-index: 60;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-collapse-outer:hover {
        background: var(--primary);
        border-color: var(--primary);
        box-shadow: 0 6px 16px rgba(16,185,129,0.3);
        transform: scale(1.1);
    }
    .btn-collapse-outer svg {
        width: 18px;
        height: 18px;
        color: #64748b;
        transition: all 0.3s ease;
    }
    .btn-collapse-outer:hover svg { color: white; }
    /* Toggle Logic */
    .btn-collapse-outer .icon-menu { display: block; }
    .btn-collapse-outer .icon-arrow { display: none; }
    
    .sidebar.sidebar-collapsed ~ .btn-collapse-outer .icon-menu,
    .sidebar-collapsed-state .btn-collapse-outer .icon-menu { display: none; }
    
    .sidebar.sidebar-collapsed ~ .btn-collapse-outer .icon-arrow,
    .sidebar-collapsed-state .btn-collapse-outer .icon-arrow { display: block; }

    /* Remove Rotation defined previously */
    .sidebar.sidebar-collapsed ~ .btn-collapse-outer svg,
    .sidebar-collapsed-state .btn-collapse-outer svg {
        transform: none !important;
    }

    /* FIX BUTTON POSITIONING */
    .sidebar.sidebar-collapsed ~ .btn-collapse-outer,
    .sidebar-collapsed-state .btn-collapse-outer {
        left: calc(var(--sidebar-collapsed-w) - 16px) !important; 
    }

    /* Fix Notifications in Collapsed State */
    .sidebar.sidebar-collapsed .nav-item { position: relative; }
    .sidebar.sidebar-collapsed .bg-red-500 {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 10px;
        height: 10px;
        padding: 0;
        font-size: 0;
        border-radius: 50%;
        margin: 0 !important;
        border: 2px solid white;
    }

    /* Fix Profile Card in Collapsed */
    .sidebar.sidebar-collapsed .role-badge { padding: 0.5rem; overflow: hidden; }
    .sidebar.sidebar-collapsed .role-badge .w-12 { margin: 0; }

    /* COMPLETELY HIDE SCROLLBAR IN COLLAPSED */
    .sidebar.sidebar-collapsed {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none !important;  /* Firefox */
        overflow-y: auto;
    }
    .sidebar.sidebar-collapsed::-webkit-scrollbar {
        width: 0px !important;
        background: transparent !important;
        -webkit-appearance: none !important;
        display: none !important;
    }
    .sidebar.sidebar-collapsed::-webkit-scrollbar-thumb {
        background: transparent !important;
        display: none !important;
    }
    .sidebar.sidebar-collapsed::-webkit-scrollbar-track {
        background: transparent !important;
        display: none !important;
    }

    /* Fixed Icon Centering */
    .sidebar.sidebar-collapsed .nav-item,
    .sidebar.sidebar-collapsed .nav-item-locked {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        width: 100%;
        margin-left: 0;
        margin-right: 0;
    }
    @media (max-width: 767.98px) {
        .btn-collapse-outer { display: none !important; }
    }
    
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
    
    /* Modern nav-item styling */
    .nav-item { 
        display: flex; 
        align-items: center; 
        gap: 0.875rem; 
        padding: 0.75rem 1rem; 
        border-radius: 0.75rem; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        color: #475569; 
        font-weight: 500;
        margin: 0.35rem 0.5rem;
        border: 1px solid transparent;
    }
    .nav-item.active { 
        background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-lighter) 100%); 
        color: var(--primary-dark); 
        border: 1px solid rgba(16, 185, 129, 0.1);
        font-weight: 600; 
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
    }
    .nav-item:hover:not(.active) { 
        background: #f1f5f9; 
        transform: translateX(4px); 
        color: #1e293b;
    }
    .nav-item:hover:not(.active) { 
        background: var(--primary-lighter); 
        transform: translateX(4px); 
        color: var(--primary-dark);
    }
    .nav-item-locked { 
        display: flex; 
        align-items: center; 
        gap: 0.75rem; 
        padding: 0.75rem 1rem; 
        border-radius: 0.75rem; 
        background: #f9fafb; 
        cursor: not-allowed; 
        opacity: 0.6;
        margin: 0.25rem 0;
    }
    .nav-item-locked:hover { background: #f3f4f6; }
    
    .tooltip { position: relative; }
    .tooltip-text { 
        position: absolute; 
        left: calc(100% + 15px); 
        top: 50%; 
        transform: translateY(-50%) translateX(10px); 
        background: #1e293b; 
        color: white; 
        padding: 0.6rem 1rem; 
        border-radius: 0.5rem; 
        white-space: nowrap; 
        opacity: 0; 
        pointer-events: none; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        font-size: 0.875rem;
        font-weight: 500;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .sidebar.sidebar-collapsed .nav-item:hover .tooltip-text,
    .sidebar.sidebar-collapsed .nav-item-locked:hover .tooltip-text { 
        opacity: 1; 
        transform: translateY(-50%) translateX(0);
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
    
    /* Modern card styles */
    .card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }
    
    /* Modern button system */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-weight: 600;
        border-radius: 0.5rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border: 1px solid transparent;
        line-height: 1.25rem;
        text-decoration: none;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
    }
    
    .btn:active { transform: translateY(1px); }
    .btn:disabled:not(.btn-loading) { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }

    /* Variants */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2), 0 2px 4px -1px rgba(16, 185, 129, 0.1);
    }
    .btn-primary:hover {
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3), 0 4px 6px -2px rgba(16, 185, 129, 0.1);
        transform: translateY(-1px);
        filter: brightness(105%);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #111827;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.2), 0 2px 4px -1px rgba(239, 68, 68, 0.1);
    }
    .btn-danger:hover {
        box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.3), 0 4px 6px -2px rgba(239, 68, 68, 0.1);
        transform: translateY(-1px);
        filter: brightness(105%);
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.2), 0 2px 4px -1px rgba(245, 158, 11, 0.1);
    }
    .btn-warning:hover {
        box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3), 0 4px 6px -2px rgba(245, 158, 11, 0.1);
        transform: translateY(-1px);
        filter: brightness(105%);
    }

    .btn-ghost {
        background: transparent;
        color: #6b7280;
    }
    .btn-ghost:hover {
        background: rgba(0,0,0,0.05);
        color: #111827;
    }
    .btn-ghost-primary { color: var(--primary-dark); }
    .btn-ghost-primary:hover { background: var(--primary-lighter); }
    .btn-ghost-danger { color: #dc2626; }
    .btn-ghost-danger:hover { background: #fef2f2; }

    /* Sizes */
    .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.75rem; border-radius: 0.375rem; }
    .btn-sm { padding: 0.375rem 0.75rem; font-size: 0.875rem; }
    .btn-md { padding: 0.625rem 1.25rem; font-size: 0.875rem; }
    .btn-lg { padding: 0.75rem 1.5rem; font-size: 1rem; }
    .btn-xl { padding: 1rem 2rem; font-size: 1.125rem; }

    
    /* Modern table styles */
    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-modern th {
        background: #f8fafc;
        padding: 0.875rem 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }
    .table-modern td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #f3f4f6;
        color: #4b5563;
    }
    .table-modern tbody tr:hover {
        background: #f8fafc;
    }
    
    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.3s ease-out; }
    
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .animate-slide-in { animation: slideIn 0.3s ease-out; }
</style>

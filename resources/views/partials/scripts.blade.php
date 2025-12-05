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
</script>

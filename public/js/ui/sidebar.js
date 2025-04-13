// public/js/ui/sidebar.js
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleSidebar = document.getElementById('toggleSidebar');

    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const isMobile = window.innerWidth < 768;
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnToggle = toggleSidebar && toggleSidebar.contains(event.target);

        if (isMobile && sidebar.classList.contains('show') && !isClickInsideSidebar && !isClickOnToggle) {
            sidebar.classList.remove('show');
        }
    });
});

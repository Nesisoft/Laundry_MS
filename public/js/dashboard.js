document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const toggleSidebarBtn = document.getElementById('toggleSidebar');

    toggleSidebarBtn.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');

        // Update icon
        const icon = this.querySelector('i');
        if (sidebar.classList.contains('collapsed')) {
            icon.className = 'fas fa-bars';
        } else {
            icon.className = 'fas fa-times';
        }
    });

    // Mobile sidebar
    if (window.innerWidth <= 768) {
        sidebar.classList.add('collapsed');
        toggleSidebarBtn.querySelector('i').className = 'fas fa-bars';

        // Add event listener for mobile toggle
        toggleSidebarBtn.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }

    // User dropdown
    const userDropdownButton = document.getElementById('userDropdownButton');
    const userDropdownMenu = document.getElementById('userDropdownMenu');

    userDropdownButton.addEventListener('click', function() {
        userDropdownMenu.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!userDropdownButton.contains(e.target) && !userDropdownMenu.contains(e.target)) {
            userDropdownMenu.classList.remove('show');
        }
    });

    // Sidebar links
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // For demo purposes, prevent navigation
            e.preventDefault();

            // Remove active class from all links
            sidebarLinks.forEach(l => l.classList.remove('active'));

            // Add active class to clicked link
            this.classList.add('active');
        });
    });

    // Load business data if available (for demo purposes)
    const businessData = JSON.parse(localStorage.getItem('businessData') || '{}');
    const adminData = JSON.parse(localStorage.getItem('adminData') || '{}');

    if (adminData.firstName && adminData.lastName) {
        const userNameElement = document.querySelector('.user-name');
        userNameElement.textContent = `${adminData.firstName} ${adminData.lastName}`;

        const avatarElement = document.querySelector('.avatar span');
        avatarElement.textContent = `${adminData.firstName.charAt(0)}${adminData.lastName.charAt(0)}`;
    }
});

/**
 * Component for displaying internet connection status
 * Only needed for operations that require internet (product key verification, SMS)
 */
document.addEventListener('DOMContentLoaded',  function() {
    const connectionStatus = document.getElementById('connectionStatus');

    if (!connectionStatus) return;

    const statusIndicator = connectionStatus.querySelector('.status-indicator');
    const statusText = connectionStatus.querySelector('.status-text');

    // Check connection status initially
    updateConnectionStatus();

    // Listen for online/offline events
    window.addEventListener('online', updateConnectionStatus);
    window.addEventListener('offline', updateConnectionStatus);

    // Update every 30 seconds
    setInterval(updateConnectionStatus, 30000);

    // Function to update connection status
    async function updateConnectionStatus() {
        try {
            // Try to fetch a small resource to check internet connectivity
            const response = await fetch('https://www.google.com/favicon.ico', {
                mode: 'no-cors',
                cache: 'no-store'
            });

            statusIndicator.classList.remove('offline');
            statusIndicator.classList.add('online');
            statusText.textContent = 'Online';
        } catch (error) {
            statusIndicator.classList.remove('online');
            statusIndicator.classList.add('offline');
            statusText.textContent = 'Offline';
        }
    }
});

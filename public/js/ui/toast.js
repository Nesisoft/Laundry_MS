/**
 * Toast notification component
 */
class ToastManager {
    constructor() {
        this.container = document.getElementById('toastContainer');

        // Create container if it doesn't exist
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'toastContainer';
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        }
    }

    /**
     * Show a toast notification
     * @param {string} message - Toast message
     * @param {string} type - Toast type (info, success, error, warning)
     * @param {number} duration - Duration in milliseconds
     */
    show(message, type = 'info', duration = 5000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        let icon = 'info-circle';
        if (type === 'success') icon = 'check-circle';
        if (type === 'error') icon = 'exclamation-circle';
        if (type === 'warning') icon = 'exclamation-triangle';

        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas fa-${icon}"></i>
            </div>
            <div class="toast-content">
                <p>${message}</p>
            </div>
            <button class="toast-close">
                <i class="fas fa-times"></i>
            </button>
        `;

        this.container.appendChild(toast);

        // Add event listener to close button
        toast.querySelector('.toast-close').addEventListener('click', () => {
            this.close(toast);
        });

        // Auto close after duration
        setTimeout(() => {
            this.close(toast);
        }, duration);

        return toast;
    }

    /**
     * Close a toast notification
     * @param {HTMLElement} toast - Toast element
     */
    close(toast) {
        if (!toast.parentNode) return;

        toast.classList.add('toast-closing');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }

    /**
     * Show an info toast
     * @param {string} message - Toast message
     * @param {number} duration - Duration in milliseconds
     */
    info(message, duration = 5000) {
        return this.show(message, 'info', duration);
    }

    /**
     * Show a success toast
     * @param {string} message - Toast message
     * @param {number} duration - Duration in milliseconds
     */
    success(message, duration = 5000) {
        return this.show(message, 'success', duration);
    }

    /**
     * Show an error toast
     * @param {string} message - Toast message
     * @param {number} duration - Duration in milliseconds
     */
    error(message, duration = 5000) {
        return this.show(message, 'error', duration);
    }

    /**
     * Show a warning toast
     * @param {string} message - Toast message
     * @param {number} duration - Duration in milliseconds
     */
    warning(message, duration = 5000) {
        return this.show(message, 'warning', duration);
    }
}

// Create a singleton instance
const toastManager = new ToastManager();

// Global function for showing toasts
function showToast(message, type = 'info', duration = 5000) {
    return toastManager.show(message, type, duration);
}

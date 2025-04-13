/**
 * Service for handling API requests to the local Laravel backend
 */
class ApiService {
    constructor(baseUrl = 'http://localhost:8000/api') {
        this.baseUrl = baseUrl;
    }

    /**
     * Make an API request to the local Laravel backend
     * @param {string} endpoint - API endpoint
     * @param {Object} options - Request options
     * @returns {Promise} - Promise with the response
     */
    async request(endpoint, options = {}) {
        const url = `${this.baseUrl}${endpoint}`;

        // Set default headers
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...options.headers
        };

        // Add auth token if available
        const token = localStorage.getItem('auth_token');
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        const requestOptions = {
            ...options,
            headers
        };

        try {
            const response = await fetch(url, requestOptions);

            // Handle unauthorized responses
            if (response.status === 401) {
                // Clear token and redirect to login
                localStorage.removeItem('auth_token');
                window.location.href = '/login.html';
                return null;
            }

            return await response.json();
        } catch (error) {
            console.error('API request failed:', error);
            throw error;
        }
    }

    /**
     * Check if internet connection is available
     * Only needed for operations that require internet (product key verification, SMS)
     * @returns {Promise<boolean>} - Promise that resolves to true if online
     */
    async isOnline() {
        try {
            // Try to fetch a small resource to check internet connectivity
            const response = await fetch('https://www.google.com/favicon.ico', {
                mode: 'no-cors',
                cache: 'no-store'
            });
            return true;
        } catch (error) {
            return false;
        }
    }

    /**
     * Verify product key (requires internet)
     * @param {string} productKey - The product key to verify
     * @returns {Promise<Object>} - Promise with the verification result
     */
    async verifyProductKey(productKey) {
        const isOnline = await this.isOnline();

        if (!isOnline) {
            throw new Error('Internet connection required to verify product key');
        }

        return this.request('/verify-product-key', {
            method: 'POST',
            body: JSON.stringify({ key: productKey })
        });
    }

    /**
     * Send SMS notification (requires internet)
     * @param {string} phoneNumber - The phone number to send SMS to
     * @param {string} message - The message to send
     * @returns {Promise<Object>} - Promise with the SMS sending result
     */
    async sendSMS(phoneNumber, message) {
        const isOnline = await this.isOnline();

        if (!isOnline) {
            throw new Error('Internet connection required to send SMS');
        }

        return this.request('/send-sms', {
            method: 'POST',
            body: JSON.stringify({ phone_number: phoneNumber, message })
        });
    }
}

// Create a singleton instance
const apiService = new ApiService();

import apiService from './api';

/**
 * Service for handling customer operations
 */
class CustomerService {

    constructor() {
        this.apiEndpoint = '/customers';
    }

    /**
     * Get all customers with filtering and pagination
     * @param {Object} params - Query parameters
     * @returns {Promise<Object>} - Promise with the customers
     */
    async getCustomers(params = {}) {
        try {
            const queryParams = new URLSearchParams();

            // Add all params to query string
            Object.entries(params).forEach(([key, value]) => {
                if (value !== undefined && value !== null && value !== '') {
                    queryParams.append(key, value);
                }
            });

            // Always include address
            queryParams.append('with_address', 'true');

            const queryString = queryParams.toString();
            const endpoint = `${this.apiEndpoint}?${queryString}`;

            const response = await apiService.request(endpoint, { method: 'GET' });

            if (response && response.success) {
                return response.data;
            }

            return null;
        } catch (error) {
            console.error('Error fetching customers:', error);
            throw error;
        }
    }

    /**
     * Get a customer by ID
     * @param {number} id - Customer ID
     * @returns {Promise<Object>} - Promise with the customer
     */
    async getCustomer(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, { method: 'GET' });

            if (response && response.success) {
                return response.data;
            }

            return null;
        } catch (error) {
            console.error('Error fetching customer:', error);
            throw error;
        }
    }

    /**
     * Create a new customer
     * @param {Object} customerData - Customer data
     * @returns {Promise<Object>} - Promise with the created customer
     */
    async createCustomer(customerData) {
        try {
            const response = await apiService.request(this.apiEndpoint, {
                method: 'POST',
                body: JSON.stringify(customerData)
            });

            if (response && response.success) {
                return response.data;
            }

            return null;
        } catch (error) {
            console.error('Error creating customer:', error);
            throw error;
        }
    }

    /**
     * Update a customer
     * @param {number} id - Customer ID
     * @param {Object} customerData - Customer data
     * @returns {Promise<Object>} - Promise with the updated customer
     */
    async updateCustomer(id, customerData) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, {
                method: 'PUT',
                body: JSON.stringify(customerData)
            });

            if (response && response.success) {
                return response.data;
            }

            return null;
        } catch (error) {
            console.error('Error updating customer:', error);
            throw error;
        }
    }

    /**
     * Archive a customer
     * @param {number} id - Customer ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async archiveCustomer(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}/archive`, {
                method: 'PUT'
            });

            return response;
        } catch (error) {
            console.error('Error archiving customer:', error);
            throw error;
        }
    }

    /**
     * Restore a customer from archive
     * @param {number} id - Customer ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async restoreCustomer(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}/restore`, {
                method: 'PUT'
            });

            return response;
        } catch (error) {
            console.error('Error restoring customer:', error);
            throw error;
        }
    }

    /**
     * Delete a customer
     * @param {number} id - Customer ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async deleteCustomer(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, {
                method: 'DELETE'
            });

            return response;
        } catch (error) {
            console.error('Error deleting customer:', error);
            throw error;
        }
    }

    /**
     * Send welcome SMS to customer (requires internet)
     * @param {Object} customer - Customer object
     * @returns {Promise<Object>} - Promise with the result
     */
    async sendWelcomeSMS(customer) {
        try {
            const message = `Welcome to our laundry service, ${customer.first_name}! Thank you for choosing us.`;
            return await apiService.sendSMS(customer.phone_number, message);
        } catch (error) {
            console.error('Error sending welcome SMS:', error);
            // Don't throw the error, just return null since SMS is not critical
            return { success: false, message: 'Failed to send SMS. Internet connection required.' };
        }
    }
}

// Create a singleton instance
const customerService = new CustomerService();

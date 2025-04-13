import apiService from './api';
import CustomerDiscount from '../models/customer-discount';

/**
 * Service for handling customer discount operations
 */
class CustomerDiscountService {
    constructor() {
        this.apiEndpoint = '/customer-discounts';
    }

    /**
     * Get all customer discounts with filtering and pagination
     * @param {Object} params - Query parameters
     * @returns {Promise<Object>} - Promise with the customer discounts
     */
    async getCustomerDiscounts(params = {}) {
        try {
            const queryParams = new URLSearchParams();

            // Add all params to query string
            Object.entries(params).forEach(([key, value]) => {
                if (value !== undefined && value !== null && value !== '') {
                    queryParams.append(key, value);
                }
            });

            const queryString = queryParams.toString();
            const endpoint = `${this.apiEndpoint}?${queryString}`;

            const response = await apiService.request(endpoint, { method: 'GET' });

            if (response && response.success) {
                // Convert raw data to CustomerDiscount objects
                const customerDiscounts = response.data.data.map(item => new CustomerDiscount(item));

                return {
                    data: customerDiscounts,
                    meta: {
                        current_page: response.data.current_page,
                        per_page: response.data.per_page,
                        total: response.data.total,
                        last_page: response.data.last_page
                    }
                };
            }

            return null;
        } catch (error) {
            console.error('Error fetching customer discounts:', error);
            throw error;
        }
    }

    /**
     * Get a customer discount by ID
     * @param {number} id - Customer discount ID
     * @returns {Promise<CustomerDiscount>} - Promise with the customer discount
     */
    async getCustomerDiscount(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, { method: 'GET' });

            if (response && response.success) {
                return new CustomerDiscount(response.data);
            }

            return null;
        } catch (error) {
            console.error('Error fetching customer discount:', error);
            throw error;
        }
    }

    /**
     * Create a new customer discount
     * @param {Object} data - Customer discount data
     * @returns {Promise<CustomerDiscount>} - Promise with the created customer discount
     */
    async createCustomerDiscount(data) {
        try {
            const response = await apiService.request(this.apiEndpoint, {
                method: 'POST',
                body: JSON.stringify(data)
            });

            if (response && response.success) {
                return new CustomerDiscount(response.data);
            }

            return null;
        } catch (error) {
            console.error('Error creating customer discount:', error);
            throw error;
        }
    }

    /**
     * Update a customer discount
     * @param {number} id - Customer discount ID
     * @param {Object} data - Customer discount data
     * @returns {Promise<CustomerDiscount>} - Promise with the updated customer discount
     */
    async updateCustomerDiscount(id, data) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, {
                method: 'PUT',
                body: JSON.stringify(data)
            });

            if (response && response.success) {
                return new CustomerDiscount(response.data);
            }

            return null;
        } catch (error) {
            console.error('Error updating customer discount:', error);
            throw error;
        }
    }

    /**
     * Delete a customer discount
     * @param {number} id - Customer discount ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async deleteCustomerDiscount(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, {
                method: 'DELETE'
            });

            return response;
        } catch (error) {
            console.error('Error deleting customer discount:', error);
            throw error;
        }
    }
}

// Create a singleton instance
const customerDiscountService = new CustomerDiscountService();

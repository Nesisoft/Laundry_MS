import apiService from './api';
import Discount from '../models/discount';

/**
 * Service for handling discount operations
 */
class DiscountService {
    constructor() {
        this.apiEndpoint = '/discounts';
    }

    /**
     * Get all discounts with filtering and pagination
     * @param {Object} params - Query parameters
     * @returns {Promise<Object>} - Promise with the discounts
     */
    async getDiscounts(params = {}) {
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
                // Convert raw data to Discount objects
                const discounts = response.data.data.map(item => new Discount(item));

                return {
                    data: discounts,
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
            console.error('Error fetching discounts:', error);
            throw error;
        }
    }

    /**
     * Get a discount by ID
     * @param {number} id - Discount ID
     * @returns {Promise<Discount>} - Promise with the discount
     */
    async getDiscount(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, { method: 'GET' });

            if (response && response.success) {
                return new Discount(response.data);
            }

            return null;
        } catch (error) {
            console.error('Error fetching discount:', error);
            throw error;
        }
    }

    /**
     * Create a new discount
     * @param {Object} discountData - Discount data
     * @returns {Promise<Discount>} - Promise with the created discount
     */
    async createDiscount(discountData) {
        try {
            const response = await apiService.request(this.apiEndpoint, {
                method: 'POST',
                body: JSON.stringify(discountData)
            });

            if (response && response.success) {
                return new Discount(response.data);
            }

            return null;
        } catch (error) {
            console.error('Error creating discount:', error);
            throw error;
        }
    }

    /**
     * Update a discount
     * @param {number} id - Discount ID
     * @param {Object} discountData - Discount data
     * @returns {Promise<Discount>} - Promise with the updated discount
     */
    async updateDiscount(id, discountData) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, {
                method: 'PUT',
                body: JSON.stringify(discountData)
            });

            if (response && response.success) {
                return new Discount(response.data);
            }

            return null;
        } catch (error) {
            console.error('Error updating discount:', error);
            throw error;
        }
    }

    /**
     * Archive a discount
     * @param {number} id - Discount ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async archiveDiscount(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}/archive`, {
                method: 'PUT'
            });

            return response;
        } catch (error) {
            console.error('Error archiving discount:', error);
            throw error;
        }
    }

    /**
     * Restore a discount from archive
     * @param {number} id - Discount ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async restoreDiscount(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}/restore`, {
                method: 'PUT'
            });

            return response;
        } catch (error) {
            console.error('Error restoring discount:', error);
            throw error;
        }
    }

    /**
     * Delete a discount
     * @param {number} id - Discount ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async deleteDiscount(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, {
                method: 'DELETE'
            });

            return response;
        } catch (error) {
            console.error('Error deleting discount:', error);
            throw error;
        }
    }
}

// Create a singleton instance
const discountService = new DiscountService();

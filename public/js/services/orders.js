/**
 * Service for handling order operations
 */
import apiService from '../api/api-service';

class OrderService {
    constructor() {
        this.apiEndpoint = '/orders';
    }

    /**
     * Get all orders with filtering and pagination
     * @param {Object} params - Query parameters
     * @returns {Promise<Object>} - Promise with the orders
     */
    async getOrders(params = {}) {
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
                return {
                    data: response.data.data,
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
            console.error('Error fetching orders:', error);
            throw error;
        }
    }

    /**
     * Get an order by ID
     * @param {number} id - Order ID
     * @returns {Promise<Object>} - Promise with the order
     */
    async getOrder(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, { method: 'GET' });

            if (response && response.success) {
                return response.data;
            }

            return null;
        } catch (error) {
            console.error('Error fetching order:', error);
            throw error;
        }
    }

    /**
     * Create a new order
     * @param {Object} data - Order data
     * @returns {Promise<Object>} - Promise with the created order
     */
    async createOrder(data) {
        try {
            const response = await apiService.request(this.apiEndpoint, {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (response && response.success) {
                return response.data;
            }

            return null;
        } catch (error) {
            console.error('Error creating order:', error);
            throw error;
        }
    }

    /**
     * Update an order
     * @param {number} id - Order ID
     * @param {Object} data - Order data
     * @returns {Promise<Object>} - Promise with the updated order
     */
    async updateOrder(id, data) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, {
                method: 'PUT',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (response && response.success) {
                return response.data;
            }

            return null;
        } catch (error) {
            console.error('Error updating order:', error);
            throw error;
        }
    }

    /**
     * Archive an order
     * @param {number} id - Order ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async archiveOrder(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}/archive`, {
                method: 'PUT'
            });

            return response;
        } catch (error) {
            console.error('Error archiving order:', error);
            throw error;
        }
    }

    /**
     * Restore an order from archive
     * @param {number} id - Order ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async restoreOrder(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}/restore`, {
                method: 'PUT'
            });

            return response;
        } catch (error) {
            console.error('Error restoring order:', error);
            throw error;
        }
    }

    /**
     * Delete an order
     * @param {number} id - Order ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async deleteOrder(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, {
                method: 'DELETE'
            });

            return response;
        } catch (error) {
            console.error('Error deleting order:', error);
            throw error;
        }
    }
}

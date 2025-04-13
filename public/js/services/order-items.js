import apiService from '../api/api-service';

/**
 * Service for handling order item operations
 */
class OrderItemService {
    constructor() {
        this.apiEndpoint = '/order-items';
    }

    /**
     * Get all order items with filtering and pagination
     * @param {Object} params - Query parameters
     * @returns {Promise<Object>} - Promise with the order items
     */
    async getOrderItems(params = {}) {
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
            console.error('Error fetching order items:', error);
            throw error;
        }
    }

    /**
     * Add items to an order
     * @param {Object} data - Order items data
     * @returns {Promise<Object>} - Promise with the created order items
     */
    async addOrderItems(data) {
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
            console.error('Error adding order items:', error);
            throw error;
        }
    }

    /**
     * Delete an order item
     * @param {number} id - Order item ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async deleteOrderItem(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, {
                method: 'DELETE'
            });

            return response;
        } catch (error) {
            console.error('Error deleting order item:', error);
            throw error;
        }
    }
}

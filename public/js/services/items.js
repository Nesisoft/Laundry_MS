import apiService from './api';
import Item from '../models/item';

/**
 * Service for handling item operations
 */
class ItemService {
    constructor() {
        this.apiEndpoint = '/items';
    }

    /**
     * Get all items with filtering and pagination
     * @param {Object} params - Query parameters
     * @returns {Promise<Object>} - Promise with the items
     */
    async getItems(params = {}) {
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
                // Convert raw data to Item objects
                const items = response.data.data.map(item => new Item(item));

                return {
                    data: items,
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
            console.error('Error fetching items:', error);
            throw error;
        }
    }

    /**
     * Get an item by ID
     * @param {number} id - Item ID
     * @returns {Promise<Item>} - Promise with the item
     */
    async getItem(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, { method: 'GET' });

            if (response && response.success) {
                return new Item(response.data);
            }

            return null;
        } catch (error) {
            console.error('Error fetching item:', error);
            throw error;
        }
    }

    /**
     * Create a new item
     * @param {FormData} formData - Item form data including image
     * @returns {Promise<Item>} - Promise with the created item
     */
    async createItem(formData) {
        try {
            const response = await apiService.request(this.apiEndpoint, {
                method: 'POST',
                body: formData,
                headers: {
                    // Remove Content-Type header to let the browser set it with the boundary
                    'Content-Type': undefined
                }
            });

            if (response && response.success) {
                return new Item(response.data);
            }

            return null;
        } catch (error) {
            console.error('Error creating item:', error);
            throw error;
        }
    }

    /**
     * Update an item
     * @param {number} id - Item ID
     * @param {FormData} formData - Item form data including image
     * @returns {Promise<Item>} - Promise with the updated item
     */
    async updateItem(id, formData) {
        try {
            // Add _method=PUT to the formData for Laravel to recognize it as a PUT request
            formData.append('_method', 'PUT');

            const response = await apiService.request(`${this.apiEndpoint}/${id}`, {
                method: 'POST', // Use POST for FormData with files
                body: formData,
                headers: {
                    // Remove Content-Type header to let the browser set it with the boundary
                    'Content-Type': undefined
                }
            });

            if (response && response.success) {
                return new Item(response.data);
            }

            return null;
        } catch (error) {
            console.error('Error updating item:', error);
            throw error;
        }
    }

    /**
     * Archive an item
     * @param {number} id - Item ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async archiveItem(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}/archive`, {
                method: 'PUT'
            });

            return response;
        } catch (error) {
            console.error('Error archiving item:', error);
            throw error;
        }
    }

    /**
     * Restore an item from archive
     * @param {number} id - Item ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async restoreItem(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}/restore`, {
                method: 'PUT'
            });

            return response;
        } catch (error) {
            console.error('Error restoring item:', error);
            throw error;
        }
    }

    /**
     * Delete an item
     * @param {number} id - Item ID
     * @returns {Promise<Object>} - Promise with the result
     */
    async deleteItem(id) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${id}`, {
                method: 'DELETE'
            });

            return response;
        } catch (error) {
            console.error('Error deleting item:', error);
            throw error;
        }
    }
}

/**
 * Service for handling local configuration operations
 */
import apiService from '../api/api-service';

class LocalConfigService {
    constructor() {
        this.apiEndpoint = '/local-configs';
    }

    /**
     * Get all configurations
     * @returns {Promise<Array>} - Promise with the configurations
     */
    async getAllConfigs() {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/all`, { method: 'GET' });

            if (response && response.success) {
                return response.data;
            }

            return null;
        } catch (error) {
            console.error('Error fetching configurations:', error);
            throw error;
        }
    }

    /**
     * Get a specific configuration by key
     * @param {string} key - Configuration key
     * @returns {Promise<Object>} - Promise with the configuration
     */
    async getConfig(key) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${key}`, { method: 'GET' });

            if (response && response.success) {
                return response.data;
            }

            return null;
        } catch (error) {
            console.error('Error fetching configuration:', error);
            throw error;
        }
    }

    /**
     * Get a configuration value by key
     * @param {string} key - Configuration key
     * @returns {Promise<string>} - Promise with the configuration value
     */
    async getConfigValue(key) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/value/${key}`, { method: 'GET' });

            if (response && response.success) {
                return response.value;
            }

            return null;
        } catch (error) {
            console.error('Error fetching configuration value:', error);
            throw error;
        }
    }

    /**
     * Add a new configuration
     * @param {Object} data - Configuration data
     * @returns {Promise<Object>} - Promise with the created configuration
     */
    async addConfig(data) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/add`, {
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
            console.error('Error adding configuration:', error);
            throw error;
        }
    }

    /**
     * Update a configuration
     * @param {Object} data - Configuration data
     * @returns {Promise<Object>} - Promise with the updated configuration
     */
    async updateConfig(data) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/update`, {
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
            console.error('Error updating configuration:', error);
            throw error;
        }
    }

    /**
     * Delete a configuration
     * @param {string} key - Configuration key
     * @returns {Promise<Object>} - Promise with the result
     */
    async deleteConfig(key) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/${key}`, {
                method: 'DELETE'
            });

            return response;
        } catch (error) {
            console.error('Error deleting configuration:', error);
            throw error;
        }
    }

    /**
     * Set all business configuration values
     * @param {Object} data - Business configuration data
     * @returns {Promise<Object>} - Promise with the result
     */
    async setAllConfigValues(data) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/set-all`, {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            return response;
        } catch (error) {
            console.error('Error setting configurations:', error);
            throw error;
        }
    }

    /**
     * Reset all configurations to default
     * @returns {Promise<Object>} - Promise with the result
     */
    async resetDefaultConfigs() {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/reset`, {
                method: 'POST'
            });

            return response;
        } catch (error) {
            console.error('Error resetting configurations:', error);
            throw error;
        }
    }

    /**
     * Upload a logo
     * @param {FormData} formData - Form data with logo file
     * @returns {Promise<Object>} - Promise with the result
     */
    async uploadLogo(formData) {
        try {
            const response = await apiService.request(`${this.apiEndpoint}/logo`, {
                method: 'POST',
                body: formData,
                headers: {
                    // Remove Content-Type header to let the browser set it with the boundary
                    'Content-Type': undefined
                }
            });

            return response;
        } catch (error) {
            console.error('Error uploading logo:', error);
            throw error;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const businessInfoForm = document.getElementById('businessInfoForm');
    const businessName = document.getElementById('businessName');
    const branchName = document.getElementById('branchName');
    const phoneNumber = document.getElementById('phoneNumber');
    const email = document.getElementById('email');
    const motto = document.getElementById('motto');
    const saveBusinessInfoBtn = document.getElementById('saveBusinessInfoBtn');
    const resetBtn = document.getElementById('resetBtn');

    // Logo elements
    const logoForm = document.getElementById('logoForm');
    const logoInput = document.getElementById('logoInput');
    const logoImg = document.getElementById('logoImg');
    const logoPreview = document.getElementById('logoPreview');
    const selectLogoBtn = document.getElementById('selectLogoBtn');
    const removeLogoBtn = document.getElementById('removeLogoBtn');

    // Config table elements
    const configTableBody = document.getElementById('configTableBody');
    const addConfigBtn = document.getElementById('addConfigBtn');

    // Config modal elements
    const configModal = document.getElementById('configModal');
    const configForm = document.getElementById('configForm');
    const configModalTitle = document.getElementById('configModalTitle');
    const configKey = document.getElementById('configKey');
    const configValue = document.getElementById('configValue');
    const saveConfigBtn = document.getElementById('saveConfigBtn');
    const cancelConfigBtn = document.getElementById('cancelConfigBtn');
    const closeConfigModalBtn = document.getElementById('closeConfigModalBtn');

    // Confirmation modal elements
    const confirmationModal = document.getElementById('confirmationModal');
    const confirmationTitle = document.getElementById('confirmationTitle');
    const confirmationMessage = document.getElementById('confirmationMessage');
    const confirmActionBtn = document.getElementById('confirmActionBtn');
    const cancelConfirmationBtn = document.getElementById('cancelConfirmationBtn');
    const closeConfirmationBtn = document.getElementById('closeConfirmationBtn');

    // State
    let currentAction = null;
    let currentConfigKey = null;
    let configs = [];
    let isEditingConfig = false;

    const localConfigService = new LocalConfigService();

    // Load data on page load
    loadBusinessInfo();
    loadLogo();
    loadConfigs();

    // Event listeners
    businessInfoForm.addEventListener('submit', function(e) {
        e.preventDefault();
        saveBusinessInfo();
    });

    resetBtn.addEventListener('click', function() {
        confirmResetConfigs();
    });

    logoPreview.addEventListener('click', function() {
        logoInput.click();
    });

    selectLogoBtn.addEventListener('click', function() {
        logoInput.click();
    });

    logoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                logoImg.src = e.target.result;
            };

            reader.readAsDataURL(this.files[0]);

            // Auto upload when file is selected
            uploadLogo();
        }
    });

    removeLogoBtn.addEventListener('click', function() {
        confirmRemoveLogo();
    });

    addConfigBtn.addEventListener('click', function() {
        openAddConfigModal();
    });

    configForm.addEventListener('submit', function(e) {
        e.preventDefault();
        saveConfig();
    });

    cancelConfigBtn.addEventListener('click', function() {
        closeModal(configModal);
    });

    closeConfigModalBtn.addEventListener('click', function() {
        closeModal(configModal);
    });

    cancelConfirmationBtn.addEventListener('click', function() {
        closeModal(confirmationModal);
    });

    closeConfirmationBtn.addEventListener('click', function() {
        closeModal(confirmationModal);
    });

    confirmActionBtn.addEventListener('click', function() {
        if (currentAction === 'reset-configs') {
            resetConfigs();
        } else if (currentAction === 'remove-logo') {
            removeLogo();
        } else if (currentAction === 'delete-config') {
            deleteConfig(currentConfigKey);
        }

        closeModal(confirmationModal);
    });

    // Functions

    async function loadBusinessInfo() {
        try {
            const keys = ['business_name', 'branch_name', 'phone_number', 'email', 'motto'];
            const promises = keys.map(key => localConfigService.getConfigValue(key));

            const values = await Promise.all(promises);

            businessName.value = values[0] || '';
            branchName.value = values[1] || '';
            phoneNumber.value = values[2] || '';
            email.value = values[3] || '';
            motto.value = values[4] || '';
        } catch (error) {
            console.error('Error loading business info:', error);
            showToast('Failed to load business information', 'error');
        }
    }

    async function loadLogo() {
        try {
            const logoUrl = await localConfigService.getConfigValue('logo');

            if (logoUrl) {
                logoImg.src = logoUrl;
            } else {
                logoImg.src = '../images/placeholder-logo.png';
            }
        } catch (error) {
            console.error('Error loading logo:', error);
            logoImg.src = '../images/placeholder-logo.png';
        }
    }

    async function loadConfigs() {
        configTableBody.innerHTML = `
            <tr>
                <td colspan="3" class="loading-cell">
                    <div class="loading-container">
                        <div class="loading-spinner"></div>
                        <p>Loading configurations...</p>
                    </div>
                </td>
            </tr>
        `;

        try {
            const result = await localConfigService.getAllConfigs();

            if (result) {
                configs = result;
                renderConfigs(result);
            } else {
                showError('Failed to load configurations');
            }
        } catch (error) {
            console.error('Error loading configurations:', error);
            showError('Failed to load configurations');
        }
    }

    function renderConfigs(configs) {
        configTableBody.innerHTML = '';

        // Filter out default configs that are managed in the business info form
        const defaultKeys = ['business_name', 'branch_name', 'phone_number', 'email', 'motto', 'logo'];
        const filteredConfigs = configs.filter(config => !defaultKeys.includes(config.key));

        if (filteredConfigs.length === 0) {
            configTableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="empty-cell">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <h3 class="empty-state-title">No Custom Configurations</h3>
                            <p class="empty-state-description">
                                You haven't added any custom configurations yet.
                            </p>
                            <button id="emptyStateAddBtn" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Configuration
                            </button>
                        </div>
                    </td>
                </tr>
            `;

            document.getElementById('emptyStateAddBtn').addEventListener('click', function() {
                openAddConfigModal();
            });

            return;
        }

        filteredConfigs.forEach(config => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${config.key}</td>
                <td>${config.value || '<em>Empty</em>'}</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-icon edit-config-btn" data-key="${config.key}" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon delete-config-btn" data-key="${config.key}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;

            configTableBody.appendChild(row);
        });

        // Add event listeners to action buttons
        document.querySelectorAll('.edit-config-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const key = this.getAttribute('data-key');
                openEditConfigModal(key);
            });
        });

        document.querySelectorAll('.delete-config-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const key = this.getAttribute('data-key');
                confirmDeleteConfig(key);
            });
        });
    }

    async function saveBusinessInfo() {
        try {
            saveBusinessInfoBtn.disabled = true;
            saveBusinessInfoBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            const formData = {
                business_name: businessName.value,
                branch_name: branchName.value,
                phone_number: phoneNumber.value,
                email: email.value,
                motto: motto.value
            };

            const result = await localConfigService.setAllConfigValues(formData);

            if (result && result.success) {
                showToast('Business information saved successfully', 'success');
            } else {
                showToast('Failed to save business information', 'error');
            }
        } catch (error) {
            console.error('Error saving business info:', error);
            showToast('Failed to save business information', 'error');
        } finally {
            saveBusinessInfoBtn.disabled = false;
            saveBusinessInfoBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
        }
    }

    function confirmResetConfigs() {
        currentAction = 'reset-configs';

        confirmationTitle.textContent = 'Reset Configurations';
        confirmationMessage.textContent = 'Are you sure you want to reset all business information to default? This action cannot be undone.';

        openModal(confirmationModal);
    }

    async function resetConfigs() {
        try {
            const result = await localConfigService.resetDefaultConfigs();

            if (result && result.success) {
                showToast('Configurations reset successfully', 'success');
                loadBusinessInfo();
                loadLogo();
            } else {
                showToast('Failed to reset configurations', 'error');
            }
        } catch (error) {
            console.error('Error resetting configs:', error);
            showToast('Failed to reset configurations', 'error');
        }
    }

    async function uploadLogo() {
        try {
            const formData = new FormData();
            formData.append('logo', logoInput.files[0]);

            const result = await localConfigService.uploadLogo(formData);

            if (result && result.success) {
                showToast('Logo uploaded successfully', 'success');
            } else {
                showToast('Failed to upload logo', 'error');
                // Reset logo to previous state
                loadLogo();
            }
        } catch (error) {
            console.error('Error uploading logo:', error);
            showToast('Failed to upload logo', 'error');
            // Reset logo to previous state
            loadLogo();
        }
    }

    function confirmRemoveLogo() {
        currentAction = 'remove-logo';

        confirmationTitle.textContent = 'Remove Logo';
        confirmationMessage.textContent = 'Are you sure you want to remove the logo?';

        openModal(confirmationModal);
    }

    async function removeLogo() {
        try {
            const result = await localConfigService.updateConfig({
                key: 'logo',
                value: null
            });

            if (result) {
                showToast('Logo removed successfully', 'success');
                logoImg.src = '../images/placeholder-logo.png';
            } else {
                showToast('Failed to remove logo', 'error');
            }
        } catch (error) {
            console.error('Error removing logo:', error);
            showToast('Failed to remove logo', 'error');
        }
    }

    function openAddConfigModal() {
        configModalTitle.textContent = 'Add Configuration';
        configForm.reset();
        configKey.readOnly = false;
        isEditingConfig = false;

        openModal(configModal);
    }

    function openEditConfigModal(key) {
        configModalTitle.textContent = 'Edit Configuration';
        configForm.reset();

        const config = configs.find(c => c.key === key);
        if (config) {
            configKey.value = config.key;
            configValue.value = config.value || '';
            configKey.readOnly = true;
            isEditingConfig = true;

            openModal(configModal);
        } else {
            showToast('Configuration not found', 'error');
        }
    }

    async function saveConfig() {
        try {
            saveConfigBtn.disabled = true;
            saveConfigBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            const formData = {
                key: configKey.value,
                value: configValue.value
            };

            let result;

            if (isEditingConfig) {
                result = await localConfigService.updateConfig(formData);

                if (result) {
                    showToast('Configuration updated successfully', 'success');
                } else {
                    showToast('Failed to update configuration', 'error');
                }
            } else {
                result = await localConfigService.addConfig(formData);

                if (result) {
                    showToast('Configuration added successfully', 'success');
                } else {
                    showToast('Failed to add configuration', 'error');
                }
            }

            closeModal(configModal);
            loadConfigs();
        } catch (error) {
            console.error('Error saving configuration:', error);
            showToast('Failed to save configuration', 'error');
        } finally {
            saveConfigBtn.disabled = false;
            saveConfigBtn.innerHTML = 'Save';
        }
    }

    function confirmDeleteConfig(key) {
        currentAction = 'delete-config';
        currentConfigKey = key;

        confirmationTitle.textContent = 'Delete Configuration';
        confirmationMessage.textContent = `Are you sure you want to delete the configuration "${key}"? This action cannot be undone.`;

        openModal(confirmationModal);
    }

    async function deleteConfig(key) {
        try {
            const result = await localConfigService.deleteConfig(key);

            if (result && result.success) {
                showToast('Configuration deleted successfully', 'success');
                loadConfigs();
            } else {
                showToast('Failed to delete configuration', 'error');
            }
        } catch (error) {
            console.error('Error deleting configuration:', error);
            showToast('Failed to delete configuration', 'error');
        }
    }

    function showError(message) {
        configTableBody.innerHTML = `
            <tr>
                <td colspan="3" class="empty-cell">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <h3 class="empty-state-title">Error</h3>
                        <p class="empty-state-description">${message}</p>
                        <button id="retryBtn" class="btn btn-primary">
                            <i class="fas fa-sync-alt"></i> Retry
                        </button>
                    </div>
                </td>
            </tr>
        `;

        document.getElementById('retryBtn').addEventListener('click', function() {
            loadConfigs();
        });
    }

    function openModal(modal) {
        modal.classList.add('show');
        document.body.classList.add('modal-open');
    }

    function closeModal(modal) {
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
    }

    function showToast(message, type = 'info') {
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

        const toastContainer = document.getElementById('toastContainer');
        toastContainer.appendChild(toast);

        // Add event listener to close button
        toast.querySelector('.toast-close').addEventListener('click', function() {
            toast.classList.add('toast-closing');
            setTimeout(() => {
                toast.remove();
            }, 300);
        });

        // Auto close after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.classList.add('toast-closing');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }
        }, 5000);
    }
});

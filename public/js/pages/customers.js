

document.addEventListener('DOMContentLoaded', function() {

    import('./customerService.js').then(module => {
        window.customerService = module.default;
        // Import customer service (assuming it's in a separate file)
        // You might need to adjust the path based on your project structure
        // For example: import customerService from './customerService.js';
        // If customerService is not a module, you might need to load it in the HTML before this script
        // For this example, I'm assuming it's globally available or you will handle the import yourself.
        // If it's globally available, you don't need to do anything here.

        // Elements
        const customersTableBody = document.getElementById('customersTableBody');
        const searchInput = document.getElementById('searchInput');
        const sexFilter = document.getElementById('sexFilter');
        const archivedFilter = document.getElementById('archivedFilter');
        const addCustomerBtn = document.getElementById('addCustomerBtn');
        const customerModal = document.getElementById('customerModal');
        const customerForm = document.getElementById('customerForm');
        const modalTitle = document.getElementById('modalTitle');
        const customerId = document.getElementById('customerId');
        const saveCustomerBtn = document.getElementById('saveCustomerBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const getLocationBtn = document.getElementById('getLocationBtn');
        const confirmationModal = document.getElementById('confirmationModal');
        const confirmationTitle = document.getElementById('confirmationTitle');
        const confirmationMessage = document.getElementById('confirmationMessage');
        const confirmActionBtn = document.getElementById('confirmActionBtn');
        const cancelConfirmationBtn = document.getElementById('cancelConfirmationBtn');
        const closeConfirmationBtn = document.getElementById('closeConfirmationBtn');

        // Pagination elements
        const paginationContainer = document.getElementById('paginationContainer');
        const paginationStart = document.getElementById('paginationStart');
        const paginationEnd = document.getElementById('paginationEnd');
        const paginationTotal = document.getElementById('paginationTotal');
        const prevPageBtn = document.getElementById('prevPageBtn');
        const nextPageBtn = document.getElementById('nextPageBtn');
        const paginationPages = document.getElementById('paginationPages');

        // State
        let currentPage = 1;
        let totalPages = 1;
        let perPage = 15;
        let totalCustomers = 0;
        let currentAction = null;
        let currentCustomerId = null;

        // Load customers on page load
        loadCustomers();

        // Event listeners
        searchInput.addEventListener('input', debounce(function() {
            currentPage = 1;
            loadCustomers();
        }, 500));

        sexFilter.addEventListener('change', function() {
            currentPage = 1;
            loadCustomers();
        });

        archivedFilter.addEventListener('change', function() {
            currentPage = 1;
            loadCustomers();
        });

        addCustomerBtn.addEventListener('click', function() {
            openAddCustomerModal();
        });

        customerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            saveCustomer();
        });

        cancelBtn.addEventListener('click', function() {
            closeModal(customerModal);
        });

        closeModalBtn.addEventListener('click', function() {
            closeModal(customerModal);
        });

        getLocationBtn.addEventListener('click', function() {
            getCurrentLocation();
        });

        cancelConfirmationBtn.addEventListener('click', function() {
            closeModal(confirmationModal);
        });

        closeConfirmationBtn.addEventListener('click', function() {
            closeModal(confirmationModal);
        });

        confirmActionBtn.addEventListener('click', function() {
            if (currentAction === 'archive') {
                archiveCustomer(currentCustomerId);
            } else if (currentAction === 'restore') {
                restoreCustomer(currentCustomerId);
            } else if (currentAction === 'delete') {
                deleteCustomer(currentCustomerId);
            }

            closeModal(confirmationModal);
        });

        prevPageBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                loadCustomers();
            }
        });

        nextPageBtn.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                loadCustomers();
            }
        });

        // Functions
        async function loadCustomers() {
            showLoading();

            try {
                const params = {
                    search: searchInput.value,
                    sex: sexFilter.value,
                    archived: archivedFilter.value,
                    page: currentPage,
                    per_page: perPage
                };

                const result = await customerService.getCustomers(params);

                if (result) {
                    renderCustomers(result.data);
                    updatePagination(result.meta);
                } else {
                    showError('Failed to load customers');
                }
            } catch (error) {
                console.error('Error loading customers:', error);
                showError('Failed to load customers');
            }
        }

        function renderCustomers(customers) {
            customersTableBody.innerHTML = '';

            if (!customers || customers.length === 0) {
                customersTableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center">No customers found</td>
                    </tr>
                `;
                return;
            }

            customers.forEach(customer => {
                const row = document.createElement('tr');

                // Apply class if customer is archived
                if (customer.archived) {
                    row.classList.add('archived-row');
                }

                row.innerHTML = `
                    <td>
                        <div class="customer-name">
                            <div class="customer-avatar-sm">
                                <span>${customer.first_name.charAt(0)}${customer.last_name.charAt(0)}</span>
                            </div>
                            <div>
                                <a href="detail.html?id=${customer.id}" class="customer-link">
                                    ${customer.first_name} ${customer.last_name}
                                </a>
                            </div>
                        </div>
                    </td>
                    <td>${customer.phone_number}</td>
                    <td>${customer.email || '-'}</td>
                    <td>${customer.sex === 'male' ? 'Male' : 'Female'}</td>
                    <td>${formatAddress(customer.address)}</td>
                    <td>
                        <span class="status-badge ${customer.archived ? 'archived' : 'active'}">
                            ${customer.archived ? 'Archived' : 'Active'}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon edit-btn" data-id="${customer.id}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            ${customer.archived ?
                                `<button class="btn-icon restore-btn" data-id="${customer.id}" title="Restore">
                                    <i class="fas fa-undo"></i>
                                </button>` :
                                `<button class="btn-icon archive-btn" data-id="${customer.id}" title="Archive">
                                    <i class="fas fa-archive"></i>
                                </button>`
                            }
                            <button class="btn-icon delete-btn" data-id="${customer.id}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;

                customersTableBody.appendChild(row);
            });

            // Add event listeners to action buttons
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    openEditCustomerModal(id);
                });
            });

            document.querySelectorAll('.archive-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    confirmArchiveCustomer(id);
                });
            });

            document.querySelectorAll('.restore-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    confirmRestoreCustomer(id);
                });
            });

            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    confirmDeleteCustomer(id);
                });
            });
        }

        function formatAddress(address) {
            if (!address) return 'No address';

            const parts = [];
            if (address.street) parts.push(address.street);
            if (address.city) parts.push(address.city);
            if (address.state) parts.push(address.state);

            return parts.join(', ') || 'No address details';
        }

        function updatePagination(meta) {
            if (!meta) return;

            totalCustomers = meta.total;
            currentPage = meta.current_page;
            totalPages = meta.last_page;
            perPage = meta.per_page;

            const start = (currentPage - 1) * perPage + 1;
            const end = Math.min(start + perPage - 1, totalCustomers);

            paginationStart.textContent = totalCustomers > 0 ? start : 0;
            paginationEnd.textContent = end;
            paginationTotal.textContent = totalCustomers;

            prevPageBtn.disabled = currentPage <= 1;
            nextPageBtn.disabled = currentPage >= totalPages;

            // Generate page numbers
            paginationPages.innerHTML = '';

            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `pagination-page ${i === currentPage ? 'active' : ''}`;
                pageBtn.textContent = i;

                pageBtn.addEventListener('click', function() {
                    currentPage = i;
                    loadCustomers();
                });

                paginationPages.appendChild(pageBtn);
            }
        }

        function openAddCustomerModal() {
            modalTitle.textContent = 'Add New Customer';
            customerId.value = '';
            customerForm.reset();

            // Set default values
            document.querySelector('input[name="sex"][value="male"]').checked = true;
            document.getElementById('country').value = 'Nigeria';

            openModal(customerModal);
        }

        async function openEditCustomerModal(id) {
            modalTitle.textContent = 'Edit Customer';
            customerId.value = id;
            customerForm.reset();

            try {
                const customer = await customerService.getCustomer(id);

                if (customer) {
                    // Fill form with customer data
                    document.getElementById('firstName').value = customer.first_name;
                    document.getElementById('lastName').value = customer.last_name;
                    document.getElementById('phoneNumber').value = customer.phone_number;
                    document.getElementById('email').value = customer.email || '';

                    // Set gender
                    document.querySelector(`input[name="sex"][value="${customer.sex}"]`).checked = true;

                    // Fill address data if available
                    if (customer.address) {
                        document.getElementById('street').value = customer.address.street || '';
                        document.getElementById('city').value = customer.address.city || '';
                        document.getElementById('state').value = customer.address.state || '';
                        document.getElementById('zipCode').value = customer.address.zip_code || '';
                        document.getElementById('country').value = customer.address.country || 'Nigeria';
                        document.getElementById('latitude').value = customer.address.latitude || '';
                        document.getElementById('longitude').value = customer.address.longitude || '';
                    }

                    openModal(customerModal);
                } else {
                    showToast('Customer not found', 'error');
                }
            } catch (error) {
                console.error('Error loading customer for edit:', error);
                showToast('Failed to load customer details', 'error');
            }
        }

        async function saveCustomer() {
            const id = customerId.value;

            // Get form data
            const formData = {
                first_name: document.getElementById('firstName').value,
                last_name: document.getElementById('lastName').value,
                phone_number: document.getElementById('phoneNumber').value,
                email: document.getElementById('email').value || null,
                sex: document.querySelector('input[name="sex"]:checked').value,

                // Address fields
                street: document.getElementById('street').value || null,
                city: document.getElementById('city').value || null,
                state: document.getElementById('state').value || null,
                zip_code: document.getElementById('zipCode').value || null,
                country: document.getElementById('country').value || null,
                latitude: document.getElementById('latitude').value || null,
                longitude: document.getElementById('longitude').value || null
            };

            try {
                saveCustomerBtn.disabled = true;
                saveCustomerBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                let result;

                if (id) {
                    // Update existing customer
                    result = await customerService.updateCustomer(id, formData);

                    if (result) {
                        showToast('Customer updated successfully', 'success');
                    } else {
                        showToast('Failed to update customer', 'error');
                    }
                } else {
                    // Create new customer
                    result = await customerService.createCustomer(formData);

                    if (result) {
                        showToast('Customer added successfully', 'success');

                        // Try to send welcome SMS if customer was created successfully
                        try {
                            await customerService.sendWelcomeSMS(result);
                        } catch (error) {
                            console.log('Could not send welcome SMS. Internet connection required.');
                            // Don't show error to user since SMS is not critical
                        }
                    } else {
                        showToast('Failed to add customer', 'error');
                    }
                }

                closeModal(customerModal);
                loadCustomers();
            } catch (error) {
                console.error('Error saving customer:', error);
                showToast('Failed to save customer', 'error');
            } finally {
                saveCustomerBtn.disabled = false;
                saveCustomerBtn.innerHTML = 'Save Customer';
            }
        }

        function confirmArchiveCustomer(id) {
            currentAction = 'archive';
            currentCustomerId = id;

            confirmationTitle.textContent = 'Archive Customer';
            confirmationMessage.textContent = 'Are you sure you want to archive this customer? They will no longer appear in the active customers list.';

            openModal(confirmationModal);
        }

        async function archiveCustomer(id) {
            try {
                const result = await customerService.archiveCustomer(id);

                if (result && result.success) {
                    showToast('Customer archived successfully', 'success');
                    loadCustomers();
                } else {
                    showToast('Failed to archive customer', 'error');
                }
            } catch (error) {
                console.error('Error archiving customer:', error);
                showToast('Failed to archive customer', 'error');
            }
        }

        function confirmRestoreCustomer(id) {
            currentAction = 'restore';
            currentCustomerId = id;

            confirmationTitle.textContent = 'Restore Customer';
            confirmationMessage.textContent = 'Are you sure you want to restore this customer? They will appear in the active customers list again.';

            openModal(confirmationModal);
        }

        async function restoreCustomer(id) {
            try {
                const result = await customerService.restoreCustomer(id);

                if (result && result.success) {
                    showToast('Customer restored successfully', 'success');
                    loadCustomers();
                } else {
                    showToast('Failed to restore customer', 'error');
                }
            } catch (error) {
                console.error('Error restoring customer:', error);
                showToast('Failed to restore customer', 'error');
            }
        }

        function confirmDeleteCustomer(id) {
            currentAction = 'delete';
            currentCustomerId = id;

            confirmationTitle.textContent = 'Delete Customer';
            confirmationMessage.textContent = 'Are you sure you want to delete this customer? This action cannot be undone.';

            openModal(confirmationModal);
        }

        async function deleteCustomer(id) {
            try {
                const result = await customerService.deleteCustomer(id);

                if (result && result.success) {
                    showToast('Customer deleted successfully', 'success');
                    loadCustomers();
                } else {
                    showToast('Failed to delete customer', 'error');
                }
            } catch (error) {
                console.error('Error deleting customer:', error);
                showToast('Failed to delete customer', 'error');
            }
        }

        function getCurrentLocation() {
            if (!navigator.geolocation) {
                showToast('Geolocation is not supported by your browser', 'error');
                return;
            }

            const locationBtn = document.getElementById('getLocationBtn');
            locationBtn.disabled = true;
            locationBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Getting location...';

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;

                    showToast('Location retrieved successfully', 'success');

                    locationBtn.disabled = false;
                    locationBtn.innerHTML = '<i class="fas fa-map-marker-alt"></i> Get Current Location';
                },
                (error) => {
                    console.error('Error getting location:', error);
                    showToast('Failed to get location: ' + getGeolocationErrorMessage(error), 'error');

                    locationBtn.disabled = false;
                    locationBtn.innerHTML = '<i class="fas fa-map-marker-alt"></i> Get Current Location';
                }
            );
        }

        function getGeolocationErrorMessage(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    return "User denied the request for geolocation.";
                case error.POSITION_UNAVAILABLE:
                    return "Location information is unavailable.";
                case error.TIMEOUT:
                    return "The request to get user location timed out.";
                case error.UNKNOWN_ERROR:
                    return "An unknown error occurred.";
                default:
                    return "Error getting location.";
            }
        }

        function showLoading() {
            customersTableBody.innerHTML = `
                <tr class="loading-row">
                    <td colspan="7" class="text-center">
                        <div class="loading-spinner"></div>
                        <p>Loading customers...</p>
                    </td>
                </tr>
            `;
        }

        function showError(message) {
            customersTableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>${message}</p>
                    </td>
                </tr>
            `;
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

        // Utility function to debounce input events
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
    });
});

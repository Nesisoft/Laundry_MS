
import CustomerDiscount from '../services/customers.js';

document.addEventListener('DOMContentLoaded', function() {
    // Import necessary services
    import('./customerService.js').then(module => {
        window.customerService = module.default;

        import('./discountService.js').then(module => {
            window.discountService = module.default;

            import('./customerDiscountService.js').then(module => {
                window.customerDiscountService = module.default;

                // Elements
                const customerDiscountsTableBody = document.getElementById('customerDiscountsTableBody');
                const customerFilter = document.getElementById('customerFilter');
                const discountFilter = document.getElementById('discountFilter');
                const statusFilter = document.getElementById('statusFilter');
                const addCustomerDiscountBtn = document.getElementById('addCustomerDiscountBtn');
                const customerDiscountModal = document.getElementById('customerDiscountModal');
                const customerDiscountForm = document.getElementById('customerDiscountForm');
                const modalTitle = document.getElementById('modalTitle');
                const customerDiscountId = document.getElementById('customerDiscountId');
                const customerSelect = document.getElementById('customerSelect');
                const discountSelect = document.getElementById('discountSelect');
                const discountInfoContainer = document.getElementById('discountInfoContainer');
                const saveCustomerDiscountBtn = document.getElementById('saveCustomerDiscountBtn');
                const cancelBtn = document.getElementById('cancelBtn');
                const closeModalBtn = document.getElementById('closeModalBtn');
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
                let totalRecords = 0;
                let currentAction = null;
                let currentRecordId = null;
                let customers = [];
                let discounts = [];

                // Load data on page load
                loadCustomers();
                loadDiscounts();
                loadCustomerDiscounts();

                // Event listeners
                customerFilter.addEventListener('change', function() {
                    currentPage = 1;
                    loadCustomerDiscounts();
                });

                discountFilter.addEventListener('change', function() {
                    currentPage = 1;
                    loadCustomerDiscounts();
                });

                statusFilter.addEventListener('change', function() {
                    currentPage = 1;
                    loadCustomerDiscounts();
                });

                addCustomerDiscountBtn.addEventListener('click', function() {
                    openAddCustomerDiscountModal();
                });

                customerDiscountForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveCustomerDiscount();
                });

                cancelBtn.addEventListener('click', function() {
                    closeModal(customerDiscountModal);
                });

                closeModalBtn.addEventListener('click', function() {
                    closeModal(customerDiscountModal);
                });

                cancelConfirmationBtn.addEventListener('click', function() {
                    closeModal(confirmationModal);
                });

                closeConfirmationBtn.addEventListener('click', function() {
                    closeModal(confirmationModal);
                });

                confirmActionBtn.addEventListener('click', function() {
                    if (currentAction === 'delete') {
                        deleteCustomerDiscount(currentRecordId);
                    }

                    closeModal(confirmationModal);
                });

                prevPageBtn.addEventListener('click', function() {
                    if (currentPage > 1) {
                        currentPage--;
                        loadCustomerDiscounts();
                    }
                });

                nextPageBtn.addEventListener('click', function() {
                    if (currentPage < totalPages) {
                        currentPage++;
                        loadCustomerDiscounts();
                    }
                });

                // Show discount info when a discount is selected
                discountSelect.addEventListener('change', function() {
                    updateDiscountInfo();
                });

                // Functions

                async function loadCustomers() {
                    try {
                        const result = await customerService.getCustomers({ per_page: 100 });

                        if (result && result.data) {
                            customers = result.data;

                            // Populate customer filter
                            customerFilter.innerHTML = '<option value="">All Customers</option>';
                            customers.forEach(customer => {
                                const option = document.createElement('option');
                                option.value = customer.id;
                                option.textContent = `${customer.first_name} ${customer.last_name}`;
                                customerFilter.appendChild(option);
                            });

                            // Populate customer select in modal
                            customerSelect.innerHTML = '<option value="">Select Customer</option>';
                            customers.forEach(customer => {
                                const option = document.createElement('option');
                                option.value = customer.id;
                                option.textContent = `${customer.first_name} ${customer.last_name}`;
                                customerSelect.appendChild(option);
                            });
                        }
                    } catch (error) {
                        console.error('Error loading customers:', error);
                        showToast('Failed to load customers', 'error');
                    }
                }

                async function loadDiscounts() {
                    try {
                        const result = await discountService.getDiscounts({ archived: false, per_page: 100 });

                        if (result && result.data) {
                            discounts = result.data;

                            // Populate discount filter
                            discountFilter.innerHTML = '<option value="">All Discounts</option>';
                            discounts.forEach(discount => {
                                const option = document.createElement('option');
                                option.value = discount.id;
                                option.textContent = discount.name || 'Unnamed Discount';
                                discountFilter.appendChild(option);
                            });

                            // Populate discount select in modal
                            discountSelect.innerHTML = '<option value="">Select Discount</option>';
                            discounts.forEach(discount => {
                                const option = document.createElement('option');
                                option.value = discount.id;
                                option.textContent = `${discount.name || 'Unnamed Discount'} (${discount.formattedValue})`;
                                discountSelect.appendChild(option);
                            });
                        }
                    } catch (error) {
                        console.error('Error loading discounts:', error);
                        showToast('Failed to load discounts', 'error');
                    }
                }

                async function loadCustomerDiscounts() {
                    showLoading();

                    try {
                        const params = {
                            page: currentPage,
                            per_page: perPage
                        };

                        // Add filters if selected
                        if (customerFilter.value) {
                            params.customer_id = customerFilter.value;
                        }

                        if (discountFilter.value) {
                            params.discount_id = discountFilter.value;
                        }

                        const result = await customerDiscountService.getCustomerDiscounts(params);

                        if (result) {
                            renderCustomerDiscounts(result.data);
                            updatePagination(result.meta);
                        } else {
                            showError('Failed to load customer discounts');
                        }
                    } catch (error) {
                        console.error('Error loading customer discounts:', error);
                        showError('Failed to load customer discounts');
                    }
                }

                function renderCustomerDiscounts(records) {
                    customerDiscountsTableBody.innerHTML = '';

                    if (!records || records.length === 0) {
                        customerDiscountsTableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="empty-cell">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-user-tag"></i>
                                        </div>
                                        <h3 class="empty-state-title">No Customer Discounts Found</h3>
                                        <p class="empty-state-description">
                                            ${customerFilter.value || discountFilter.value
                                                ? 'No records match your current filters.'
                                                : 'Start by assigning discounts to your customers.'}
                                        </p>
                                        <button id="emptyStateAddBtn" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Assign Discount
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;

                        document.getElementById('emptyStateAddBtn').addEventListener('click', function() {
                            openAddCustomerDiscountModal();
                        });

                        return;
                    }

                    // Filter by status if selected
                    if (statusFilter.value) {
                        records = records.filter(record => record.status === statusFilter.value);

                        if (records.length === 0) {
                            customerDiscountsTableBody.innerHTML = `
                                <tr>
                                    <td colspan="6" class="empty-cell">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-filter"></i>
                                            </div>
                                            <h3 class="empty-state-title">No Matching Records</h3>
                                            <p class="empty-state-description">
                                                No ${statusFilter.value} customer discounts found with the current filters.
                                            </p>
                                            <button id="clearFiltersBtn" class="btn btn-secondary">
                                                <i class="fas fa-times"></i> Clear Filters
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;

                            document.getElementById('clearFiltersBtn').addEventListener('click', function() {
                                statusFilter.value = '';
                                loadCustomerDiscounts();
                            });

                            return;
                        }
                    }

                    records.forEach(record => {
                        const row = document.createElement('tr');

                        // Format expiration date
                        let expirationDate = 'No expiration date';
                        if (record.customer_expiration_date) {
                            const date = new Date(record.customer_expiration_date);
                            expirationDate = date.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });
                        } else if (record.discount && record.discount.expiration_date) {
                            const date = new Date(record.discount.expiration_date);
                            expirationDate = date.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            }) + ' (from discount)';
                        }

                        row.innerHTML = `
                            <td>${record.customerName}</td>
                            <td>${record.discountName}</td>
                            <td>${record.discountValue}</td>
                            <td>${expirationDate}</td>
                            <td>
                                <span class="status-badge ${record.status}">
                                    ${record.status.charAt(0).toUpperCase() + record.status.slice(1)}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon edit-btn" data-id="${record.id}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon delete-btn" data-id="${record.id}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        `;

                        customerDiscountsTableBody.appendChild(row);
                    });

                    // Add event listeners to action buttons
                    document.querySelectorAll('.edit-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            openEditCustomerDiscountModal(id);
                        });
                    });

                    document.querySelectorAll('.delete-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            confirmDeleteCustomerDiscount(id);
                        });
                    });
                }

                function updatePagination(meta) {
                    if (!meta) return;

                    totalRecords = meta.total;
                    currentPage = meta.current_page;
                    totalPages = meta.last_page;
                    perPage = meta.per_page;

                    const start = (currentPage - 1) * perPage + 1;
                    const end = Math.min(start + perPage - 1, totalRecords);

                    paginationStart.textContent = totalRecords > 0 ? start : 0;
                    paginationEnd.textContent = end;
                    paginationTotal.textContent = totalRecords;

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
                            loadCustomerDiscounts();
                        });

                        paginationPages.appendChild(pageBtn);
                    }
                }

                function openAddCustomerDiscountModal() {
                    modalTitle.textContent = 'Assign Discount to Customer';
                    customerDiscountId.value = '';
                    customerDiscountForm.reset();
                    discountInfoContainer.innerHTML = '';

                    // Set min date for expiration to today
                    const today = new Date().toISOString().split('T')[0];
                    document.getElementById('customerExpirationDate').min = today;

                    openModal(customerDiscountModal);
                }

                async function openEditCustomerDiscountModal(id) {
                    modalTitle.textContent = 'Edit Customer Discount';
                    customerDiscountId.value = id;
                    customerDiscountForm.reset();
                    discountInfoContainer.innerHTML = '';

                    try {
                        const record = await customerDiscountService.getCustomerDiscount(id);

                        if (record) {
                            // Fill form with data
                            customerSelect.value = record.customer_id;
                            discountSelect.value = record.discount_id;

                            if (record.customer_expiration_date) {
                                // Format date for input (YYYY-MM-DD)
                                const expiryDate = new Date(record.customer_expiration_date);
                                const formattedDate = expiryDate.toISOString().split('T')[0];
                                document.getElementById('customerExpirationDate').value = formattedDate;
                            }

                            updateDiscountInfo();

                            // Set min date for expiration to today
                            const today = new Date().toISOString().split('T')[0];
                            document.getElementById('customerExpirationDate').min = today;

                            openModal(customerDiscountModal);
                        } else {
                            showToast('Record not found', 'error');
                        }
                    } catch (error) {
                        console.error('Error loading record for edit:', error);
                        showToast('Failed to load record details', 'error');
                    }
                }

                function updateDiscountInfo() {
                    const discountId = discountSelect.value;
                    discountInfoContainer.innerHTML = '';

                    if (!discountId) return;

                    const discount = discounts.find(d => d.id == discountId);
                    if (!discount) return;

                    let expirationText = 'No expiration date';
                    if (discount.expiration_date) {
                        const expiryDate = new Date(discount.expiration_date);
                        expirationText = expiryDate.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                    }

                    discountInfoContainer.innerHTML = `
                        <div class="discount-info">
                            <div class="discount-info-header">
                                <div class="discount-info-title">Discount Details</div>
                                <div class="discount-info-value">${discount.formattedValue}</div>
                            </div>
                            <div class="discount-info-detail">
                                <i class="fas fa-tag"></i>
                                <span>Type: ${discount.type === 'percentage' ? 'Percentage' : 'Fixed Amount'}</span>
                            </div>
                            <div class="discount-info-detail">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Discount Expiration: ${expirationText}</span>
                            </div>
                            <div class="discount-info-detail">
                                <i class="fas fa-info-circle"></i>
                                <span>Description: ${discount.description || 'No description provided'}</span>
                            </div>
                        </div>
                    `;
                }

                async function saveCustomerDiscount() {
                    const id = customerDiscountId.value;

                    // Get form data
                    const formData = {
                        customer_id: customerSelect.value,
                        discount_id: discountSelect.value,
                        customer_expiration_date: document.getElementById('customerExpirationDate').value || null
                    };

                    try {
                        saveCustomerDiscountBtn.disabled = true;
                        saveCustomerDiscountBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                        let result;

                        if (id) {
                            // Update existing record
                            result = await customerDiscountService.updateCustomerDiscount(id, formData);

                            if (result) {
                                showToast('Customer discount updated successfully', 'success');
                            } else {
                                showToast('Failed to update customer discount', 'error');
                            }
                        } else {
                            // Create new record
                            result = await customerDiscountService.createCustomerDiscount(formData);

                            if (result) {
                                showToast('Discount assigned successfully', 'success');
                            } else {
                                showToast('Failed to assign discount', 'error');
                            }
                        }

                        closeModal(customerDiscountModal);
                        loadCustomerDiscounts();
                    } catch (error) {
                        console.error('Error saving customer discount:', error);
                        showToast('Failed to save customer discount', 'error');
                    } finally {
                        saveCustomerDiscountBtn.disabled = false;
                        saveCustomerDiscountBtn.innerHTML = 'Assign Discount';
                    }
                }

                function confirmDeleteCustomerDiscount(id) {
                    currentAction = 'delete';
                    currentRecordId = id;

                    confirmationTitle.textContent = 'Remove Discount';
                    confirmationMessage.textContent = 'Are you sure you want to remove this discount from the customer? This action cannot be undone.';

                    openModal(confirmationModal);
                }

                async function deleteCustomerDiscount(id) {
                    try {
                        const result = await customerDiscountService.deleteCustomerDiscount(id);

                        if (result && result.success) {
                            showToast('Discount removed successfully', 'success');
                            loadCustomerDiscounts();
                        } else {
                            showToast('Failed to remove discount', 'error');
                        }
                    } catch (error) {
                        console.error('Error deleting customer discount:', error);
                        showToast('Failed to remove discount', 'error');
                    }
                }

                function showLoading() {
                    customerDiscountsTableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="loading-cell">
                                <div class="loading-container">
                                    <div class="loading-spinner"></div>
                                    <p>Loading customer discounts...</p>
                                </div>
                            </td>
                        </tr>
                    `;
                }

                function showError(message) {
                    customerDiscountsTableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="empty-cell">
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
                        loadCustomerDiscounts();
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
        });
    });


});

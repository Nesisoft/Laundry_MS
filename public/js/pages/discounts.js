document.addEventListener('DOMContentLoaded', function() {
    // Import discount service
    import('./discountService.js').then(module => {
        window.discountService = module.default;

        // Elements
        const discountCards = document.getElementById('discountCards');
        const statusFilter = document.getElementById('statusFilter');
        const typeFilter = document.getElementById('typeFilter');
        const addDiscountBtn = document.getElementById('addDiscountBtn');
        const discountModal = document.getElementById('discountModal');
        const discountForm = document.getElementById('discountForm');
        const modalTitle = document.getElementById('modalTitle');
        const discountId = document.getElementById('discountId');
        const saveDiscountBtn = document.getElementById('saveDiscountBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const confirmationModal = document.getElementById('confirmationModal');
        const confirmationTitle = document.getElementById('confirmationTitle');
        const confirmationMessage = document.getElementById('confirmationMessage');
        const confirmActionBtn = document.getElementById('confirmActionBtn');
        const cancelConfirmationBtn = document.getElementById('cancelConfirmationBtn');
        const closeConfirmationBtn = document.getElementById('closeConfirmationBtn');
        const typeSelect = document.getElementById('type');
        const valueHint = document.getElementById('valueHint');

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
        let totalDiscounts = 0;
        let currentAction = null;
        let currentDiscountId = null;

        // Load discounts on page load
        loadDiscounts();

        // Event listeners
        statusFilter.addEventListener('change', function() {
            currentPage = 1;
            loadDiscounts();
        });

        typeFilter.addEventListener('change', function() {
            currentPage = 1;
            loadDiscounts();
        });

        addDiscountBtn.addEventListener('click', function() {
            openAddDiscountModal();
        });

        discountForm.addEventListener('submit', function(e) {
            e.preventDefault();
            saveDiscount();
        });

        cancelBtn.addEventListener('click', function() {
            closeModal(discountModal);
        });

        closeModalBtn.addEventListener('click', function() {
            closeModal(discountModal);
        });

        cancelConfirmationBtn.addEventListener('click', function() {
            closeModal(confirmationModal);
        });

        closeConfirmationBtn.addEventListener('click', function() {
            closeModal(confirmationModal);
        });

        confirmActionBtn.addEventListener('click', function() {
            if (currentAction === 'archive') {
                archiveDiscount(currentDiscountId);
            } else if (currentAction === 'restore') {
                restoreDiscount(currentDiscountId);
            } else if (currentAction === 'delete') {
                deleteDiscount(currentDiscountId);
            }

            closeModal(confirmationModal);
        });

        prevPageBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                loadDiscounts();
            }
        });

        nextPageBtn.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                loadDiscounts();
            }
        });

        // Update value hint based on discount type
        typeSelect.addEventListener('change', function() {
            updateValueHint();
        });

        // Functions

        async function loadDiscounts() {
            showLoading();

            try {
                const params = {
                    page: currentPage,
                    per_page: perPage
                };

                // Handle status filter
                if (statusFilter.value === 'active') {
                    params.archived = false;
                } else if (statusFilter.value === 'archived') {
                    params.archived = true;
                }

                const result = await window.discountService.getDiscounts(params);

                if (result) {
                    renderDiscounts(result.data);
                    updatePagination(result.meta);
                } else {
                    showError('Failed to load discounts');
                }
            } catch (error) {
                console.error('Error loading discounts:', error);
                showError('Failed to load discounts');
            }
        }

        function renderDiscounts(discounts) {
            discountCards.innerHTML = '';

            if (!discounts || discounts.length === 0) {
                discountCards.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <h3 class="empty-state-title">No Discounts Found</h3>
                        <p class="empty-state-description">
                            ${statusFilter.value === 'archived'
                                ? 'There are no archived discounts. Active discounts will appear here when archived.'
                                : 'Start by adding your first discount to offer special deals to your customers.'}
                        </p>
                        <button id="emptyStateAddBtn" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Discount
                        </button>
                    </div>
                `;

                document.getElementById('emptyStateAddBtn').addEventListener('click', function() {
                    openAddDiscountModal();
                });

                return;
            }

            // Filter by type if selected
            if (typeFilter.value) {
                discounts = discounts.filter(discount => discount.type === typeFilter.value);

                if (discounts.length === 0) {
                    discountCards.innerHTML = `
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-filter"></i>
                            </div>
                            <h3 class="empty-state-title">No Matching Discounts</h3>
                            <p class="empty-state-description">
                                No ${typeFilter.value === 'percentage' ? 'percentage' : 'fixed amount'} discounts found with the current filters.
                            </p>
                            <button id="clearFiltersBtn" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Clear Filters
                            </button>
                        </div>
                    `;

                    document.getElementById('clearFiltersBtn').addEventListener('click', function() {
                        typeFilter.value = '';
                        loadDiscounts();
                    });

                    return;
                }
            }

            discounts.forEach(discount => {
                const card = document.createElement('div');
                card.className = `discount-card ${discount.status}`;

                // Format expiration date
                let expirationText = 'No expiration date';
                if (discount.expiration_date) {
                    const expiryDate = new Date(discount.expiration_date);
                    expirationText = expiryDate.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                }

                card.innerHTML = `
                    <div class="discount-badge ${discount.type}">
                        ${discount.type === 'percentage' ? 'Percentage' : 'Fixed Amount'}
                    </div>
                    <div class="discount-card-header">
                        <h3 class="discount-card-title">${discount.name || 'Unnamed Discount'}</h3>
                        <div class="discount-card-value">${discount.formattedValue}</div>
                    </div>
                    <div class="discount-card-content">
                        <div class="discount-card-description">
                            ${discount.description || 'No description provided'}
                        </div>
                        <div class="discount-card-meta">
                            <div class="discount-card-meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Expires: ${expirationText}</span>
                            </div>
                            <div class="discount-card-meta-item">
                                <i class="fas fa-clock"></i>
                                <span>Created: ${new Date(discount.created_at).toLocaleDateString()}</span>
                            </div>
                        </div>
                    </div>
                    <div class="discount-card-footer">
                        <div class="discount-card-status ${discount.status}">
                            ${discount.status.charAt(0).toUpperCase() + discount.status.slice(1)}
                        </div>
                        <div class="discount-card-actions">
                            <button class="btn-icon edit-btn" data-id="${discount.id}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            ${discount.archived ?
                                `<button class="btn-icon restore-btn" data-id="${discount.id}" title="Restore">
                                    <i class="fas fa-undo"></i>
                                </button>` :
                                `<button class="btn-icon archive-btn" data-id="${discount.id}" title="Archive">
                                    <i class="fas fa-archive"></i>
                                </button>`
                            }
                            <button class="btn-icon delete-btn" data-id="${discount.id}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;

                discountCards.appendChild(card);
            });

            // Add event listeners to action buttons
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    openEditDiscountModal(id);
                });
            });

            document.querySelectorAll('.archive-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    confirmArchiveDiscount(id);
                });
            });

            document.querySelectorAll('.restore-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    confirmRestoreDiscount(id);
                });
            });

            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    confirmDeleteDiscount(id);
                });
            });
        }

        function updatePagination(meta) {
            if (!meta) return;

            totalDiscounts = meta.total;
            currentPage = meta.current_page;
            totalPages = meta.last_page;
            perPage = meta.per_page;

            const start = (currentPage - 1) * perPage + 1;
            const end = Math.min(start + perPage - 1, totalDiscounts);

            paginationStart.textContent = totalDiscounts > 0 ? start : 0;
            paginationEnd.textContent = end;
            paginationTotal.textContent = totalDiscounts;

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
                    loadDiscounts();
                });

                paginationPages.appendChild(pageBtn);
            }
        }

        function openAddDiscountModal() {
            modalTitle.textContent = 'Add New Discount';
            discountId.value = '';
            discountForm.reset();

            // Set default values
            document.getElementById('type').value = 'percentage';
            updateValueHint();

            // Set min date for expiration to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('expirationDate').min = today;

            openModal(discountModal);
        }

        async function openEditDiscountModal(id) {
            modalTitle.textContent = 'Edit Discount';
            discountId.value = id;
            discountForm.reset();

            try {
                const discount = await window.discountService.getDiscount(id);

                if (discount) {
                    // Fill form with discount data
                    document.getElementById('name').value = discount.name || '';
                    document.getElementById('type').value = discount.type;
                    document.getElementById('value').value = discount.value;
                    document.getElementById('description').value = discount.description || '';

                    if (discount.expiration_date) {
                        // Format date for input (YYYY-MM-DD)
                        const expiryDate = new Date(discount.expiration_date);
                        const formattedDate = expiryDate.toISOString().split('T')[0];
                        document.getElementById('expirationDate').value = formattedDate;
                    }

                    updateValueHint();

                    // Set min date for expiration to today
                    const today = new Date().toISOString().split('T')[0];
                    document.getElementById('expirationDate').min = today;

                    openModal(discountModal);
                } else {
                    showToast('Discount not found', 'error');
                }
            } catch (error) {
                console.error('Error loading discount for edit:', error);
                showToast('Failed to load discount details', 'error');
            }
        }

        async function saveDiscount() {
            const id = discountId.value;

            // Get form data
            const formData = {
                name: document.getElementById('name').value || null,
                type: document.getElementById('type').value,
                value: parseFloat(document.getElementById('value').value),
                description: document.getElementById('description').value || null,
                expiration_date: document.getElementById('expirationDate').value || null
            };

            try {
                saveDiscountBtn.disabled = true;
                saveDiscountBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                let result;

                if (id) {
                    // Update existing discount
                    result = await window.discountService.updateDiscount(id, formData);

                    if (result) {
                        showToast('Discount updated successfully', 'success');
                    } else {
                        showToast('Failed to update discount', 'error');
                    }
                } else {
                    // Create new discount
                    result = await window.discountService.createDiscount(formData);

                    if (result) {
                        showToast('Discount added successfully', 'success');
                    } else {
                        showToast('Failed to add discount', 'error');
                    }
                }

                closeModal(discountModal);
                loadDiscounts();
            } catch (error) {
                console.error('Error saving discount:', error);
                showToast('Failed to save discount', 'error');
            } finally {
                saveDiscountBtn.disabled = false;
                saveDiscountBtn.innerHTML = 'Save Discount';
            }
        }

        function confirmArchiveDiscount(id) {
            currentAction = 'archive';
            currentDiscountId = id;

            confirmationTitle.textContent = 'Archive Discount';
            confirmationMessage.textContent = 'Are you sure you want to archive this discount? It will no longer be available for use.';

            openModal(confirmationModal);
        }

        async function archiveDiscount(id) {
            try {
                const result = await window.discountService.archiveDiscount(id);

                if (result && result.success) {
                    showToast('Discount archived successfully', 'success');
                    loadDiscounts();
                } else {
                    showToast('Failed to archive discount', 'error');
                }
            } catch (error) {
                console.error('Error archiving discount:', error);
                showToast('Failed to archive discount', 'error');
            }
        }

        function confirmRestoreDiscount(id) {
            currentAction = 'restore';
            currentDiscountId = id;

            confirmationTitle.textContent = 'Restore Discount';
            confirmationMessage.textContent = 'Are you sure you want to restore this discount? It will be available for use again.';

            openModal(confirmationModal);
        }

        async function restoreDiscount(id) {
            try {
                const result = await window.discountService.restoreDiscount(id);

                if (result && result.success) {
                    showToast('Discount restored successfully', 'success');
                    loadDiscounts();
                } else {
                    showToast('Failed to restore discount', 'error');
                }
            } catch (error) {
                console.error('Error restoring discount:', error);
                showToast('Failed to restore discount', 'error');
            }
        }

        function confirmDeleteDiscount(id) {
            currentAction = 'delete';
            currentDiscountId = id;

            confirmationTitle.textContent = 'Delete Discount';
            confirmationMessage.textContent = 'Are you sure you want to delete this discount? This action cannot be undone.';

            openModal(confirmationModal);
        }

        async function deleteDiscount(id) {
            try {
                const result = await window.discountService.deleteDiscount(id);

                if (result && result.success) {
                    showToast('Discount deleted successfully', 'success');
                    loadDiscounts();
                } else {
                    showToast('Failed to delete discount', 'error');
                }
            } catch (error) {
                console.error('Error deleting discount:', error);
                showToast('Failed to delete discount', 'error');
            }
        }

        function updateValueHint() {
            const type = document.getElementById('type').value;
            const hint = document.getElementById('valueHint');

            if (type === 'percentage') {
                hint.textContent = 'Enter percentage (e.g., 10 for 10%)';
            } else {
                hint.textContent = 'Enter amount in Naira (e.g., 1000)';
            }
        }

        function showLoading() {
            discountCards.innerHTML = `
                <div class="loading-container">
                    <div class="loading-spinner"></div>
                    <p>Loading discounts...</p>
                </div>
            `;
        }

        function showError(message) {
            discountCards.innerHTML = `
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
            `;

            document.getElementById('retryBtn').addEventListener('click', function() {
                loadDiscounts();
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

// Make sure ItemService is properly imported or available globally
// You can either import it at the top:
import ItemService from '../services/items';

// Or ensure it's included in your HTML before the items.js script
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const itemsGrid = document.getElementById('itemsGrid');
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const archivedFilter = document.getElementById('archivedFilter');
    const addItemBtn = document.getElementById('addItemBtn');
    const itemModal = document.getElementById('itemModal');
    const itemForm = document.getElementById('itemForm');
    const modalTitle = document.getElementById('modalTitle');
    const itemId = document.getElementById('itemId');
    const saveItemBtn = document.getElementById('saveItemBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const imageInput = document.getElementById('image');
    const previewImg = document.getElementById('previewImg');
    const selectImageBtn = document.getElementById('selectImageBtn');
    const imagePreview = document.getElementById('imagePreview');

    // Detail Modal Elements
    const itemDetailModal = document.getElementById('itemDetailModal');
    const detailModalTitle = document.getElementById('detailModalTitle');
    const detailImage = document.getElementById('detailImage');
    const detailName = document.getElementById('detailName');
    const detailPrice = document.getElementById('detailPrice');
    const detailCategory = document.getElementById('detailCategory');
    const detailCreatedAt = document.getElementById('detailCreatedAt');
    const detailAddedBy = document.getElementById('detailAddedBy');
    const detailStatus = document.getElementById('detailStatus');
    const editItemBtn = document.getElementById('editItemBtn');
    const archiveItemBtn = document.getElementById('archiveItemBtn');
    const restoreItemBtn = document.getElementById('restoreItemBtn');
    const deleteItemBtn = document.getElementById('deleteItemBtn');
    const closeDetailModalBtn = document.getElementById('closeDetailModalBtn');

    // Confirmation Modal Elements
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
    let totalItems = 0;
    let currentAction = null;
    let currentItemId = null;
    let searchTimeout = null;
    let currentItem = null;
    let categories = [];

    // Create item service instance
    const ItemService = new ItemService();

    // Load items on page load
    loadItems();
    loadCategories();

    // Event listeners
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadItems();
        }, 500);
    });

    categoryFilter.addEventListener('change', function() {
        currentPage = 1;
        loadItems();
    });

    archivedFilter.addEventListener('change', function() {
        currentPage = 1;
        loadItems();
    });

    addItemBtn.addEventListener('click', function() {
        openAddItemModal();
    });

    itemForm.addEventListener('submit', function(e) {
        e.preventDefault();
        saveItem();
    });

    cancelBtn.addEventListener('click', function() {
        closeModal(itemModal);
    });

    closeModalBtn.addEventListener('click', function() {
        closeModal(itemModal);
    });

    closeDetailModalBtn.addEventListener('click', function() {
        closeModal(itemDetailModal);
    });

    cancelConfirmationBtn.addEventListener('click', function() {
        closeModal(confirmationModal);
    });

    closeConfirmationBtn.addEventListener('click', function() {
        closeModal(confirmationModal);
    });

    confirmActionBtn.addEventListener('click', function() {
        if (currentAction === 'archive') {
            archiveItem(currentItemId);
        } else if (currentAction === 'restore') {
            restoreItem(currentItemId);
        } else if (currentAction === 'delete') {
            deleteItem(currentItemId);
        }

        closeModal(confirmationModal);
    });

    prevPageBtn.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            loadItems();
        }
    });

    nextPageBtn.addEventListener('click', function() {
        if (currentPage < totalPages) {
            currentPage++;
            loadItems();
        }
    });

    // Image upload handling
    selectImageBtn.addEventListener('click', function() {
        imageInput.click();
    });

    imagePreview.addEventListener('click', function() {
        imageInput.click();
    });

    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImg.src = e.target.result;
            };

            reader.readAsDataURL(this.files[0]);
        }
    });

    // Detail modal action buttons
    editItemBtn.addEventListener('click', function() {
        closeModal(itemDetailModal);
        openEditItemModal(currentItem.id);
    });

    archiveItemBtn.addEventListener('click', function() {
        closeModal(itemDetailModal);
        confirmArchiveItem(currentItem.id);
    });

    restoreItemBtn.addEventListener('click', function() {
        closeModal(itemDetailModal);
        confirmRestoreItem(currentItem.id);
    });

    deleteItemBtn.addEventListener('click', function() {
        closeModal(itemDetailModal);
        confirmDeleteItem(currentItem.id);
    });

    // Functions

    async function loadItems() {
        showLoading();

        try {
            const params = {
                page: currentPage,
                per_page: perPage
            };

            // Add search term if provided
            if (searchInput.value.trim()) {
                params.search = searchInput.value.trim();
            }

            // Add category filter if selected
            if (categoryFilter.value) {
                params.category = categoryFilter.value;
            }

            // Add archived filter if selected
            if (archivedFilter.value !== '') {
                params.archived = archivedFilter.value;
            }

            const result = await itemService.getItems(params);

            if (result) {
                renderItems(result.data);
                updatePagination(result.meta);
            } else {
                showError('Failed to load items');
            }
        } catch (error) {
            console.error('Error loading items:', error);
            showError('Failed to load items');
        }
    }

    function loadCategories() {
        // This would typically come from an API, but for now we'll use a static list
        categories = ['laundry', 'cleaning', 'supplies', 'other'];

        // Populate category filter
        categoryFilter.innerHTML = '<option value="">All Categories</option>';
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category;
            option.textContent = category.charAt(0).toUpperCase() + category.slice(1);
            categoryFilter.appendChild(option);
        });
    }

    function renderItems(items) {
        itemsGrid.innerHTML = '';

        if (!items || items.length === 0) {
            itemsGrid.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3 class="empty-state-title">No Items Found</h3>
                    <p class="empty-state-description">
                        ${searchInput.value.trim() || categoryFilter.value || archivedFilter.value !== ''
                            ? 'No items match your current filters. Try adjusting your search criteria.'
                            : 'Start by adding your first item to manage your inventory.'}
                    </p>
                    <button id="emptyStateAddBtn" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>
            `;

            document.getElementById('emptyStateAddBtn').addEventListener('click', function() {
                openAddItemModal();
            });

            return;
        }

        items.forEach(item => {
            const card = document.createElement('div');
            card.className = `item-card ${item.status}`;

            card.innerHTML = `
                <div class="item-card-image">
                    <img src="${item.imageUrl || '../images/placeholder-image.png'}" alt="${item.name}">
                </div>
                <div class="item-card-content">
                    <div class="item-card-header">
                        <h3 class="item-card-title">${item.name}</h3>
                        <div class="item-card-price">${item.formattedAmount}</div>
                    </div>
                    <div class="item-card-category">
                        ${item.category ? item.category.charAt(0).toUpperCase() + item.category.slice(1) : 'Uncategorized'}
                    </div>
                    <div class="item-card-footer">
                        <div class="item-card-status ${item.status}">
                            ${item.status.charAt(0).toUpperCase() + item.status.slice(1)}
                        </div>
                        <div class="item-card-actions">
                            <button class="btn-icon view-btn" data-id="${item.id}" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-icon edit-btn" data-id="${item.id}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            ${item.archived ?
                                `<button class="btn-icon restore-btn" data-id="${item.id}" title="Restore">
                                    <i class="fas fa-undo"></i>
                                </button>` :
                                `<button class="btn-icon archive-btn" data-id="${item.id}" title="Archive">
                                    <i class="fas fa-archive"></i>
                                </button>`
                            }
                            <button class="btn-icon delete-btn" data-id="${item.id}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            itemsGrid.appendChild(card);
        });

        // Add event listeners to action buttons
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openItemDetailModal(id);
            });
        });

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openEditItemModal(id);
            });
        });

        document.querySelectorAll('.archive-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                confirmArchiveItem(id);
            });
        });

        document.querySelectorAll('.restore-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                confirmRestoreItem(id);
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                confirmDeleteItem(id);
            });
        });
    }

    function updatePagination(meta) {
        if (!meta) return;

        totalItems = meta.total;
        currentPage = meta.current_page;
        totalPages = meta.last_page;
        perPage = meta.per_page;

        const start = (currentPage - 1) * perPage + 1;
        const end = Math.min(start + perPage - 1, totalItems);

        paginationStart.textContent = totalItems > 0 ? start : 0;
        paginationEnd.textContent = end;
        paginationTotal.textContent = totalItems;

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
                loadItems();
            });

            paginationPages.appendChild(pageBtn);
        }
    }

    function openAddItemModal() {
        modalTitle.textContent = 'Add New Item';
        itemId.value = '';
        itemForm.reset();
        previewImg.src = '../images/placeholder-image.png';

        openModal(itemModal);
    }

    async function openEditItemModal(id) {
        modalTitle.textContent = 'Edit Item';
        itemId.value = id;
        itemForm.reset();
        previewImg.src = '../images/placeholder-image.png';

        try {
            const item = await itemService.getItem(id);

            if (item) {
                // Fill form with item data
                document.getElementById('name').value = item.name;
                document.getElementById('amount').value = item.amount;
                document.getElementById('category').value = item.category || '';

                if (item.imageUrl) {
                    previewImg.src = item.imageUrl;
                }

                openModal(itemModal);
            } else {
                showToast('Item not found', 'error');
            }
        } catch (error) {
            console.error('Error loading item for edit:', error);
            showToast('Failed to load item details', 'error');
        }
    }

    async function openItemDetailModal(id) {
        try {
            const item = await itemService.getItem(id);

            if (item) {
                currentItem = item;

                // Fill detail modal with item data
                detailModalTitle.textContent = 'Item Details';
                detailName.textContent = item.name;
                detailPrice.textContent = item.formattedAmount;
                detailCategory.textContent = item.category
                    ? item.category.charAt(0).toUpperCase() + item.category.slice(1)
                    : 'Uncategorized';

                const createdDate = new Date(item.created_at);
                detailCreatedAt.textContent = createdDate.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });

                detailAddedBy.textContent = item.added_by || 'Unknown';

                detailStatus.textContent = item.status.charAt(0).toUpperCase() + item.status.slice(1);
                detailStatus.className = item.status;

                if (item.imageUrl) {
                    detailImage.src = item.imageUrl;
                } else {
                    detailImage.src = '../images/placeholder-image.png';
                }

                // Show/hide appropriate action buttons
                if (item.archived) {
                    archiveItemBtn.style.display = 'none';
                    restoreItemBtn.style.display = 'block';
                } else {
                    archiveItemBtn.style.display = 'block';
                    restoreItemBtn.style.display = 'none';
                }

                openModal(itemDetailModal);
            } else {
                showToast('Item not found', 'error');
            }
        } catch (error) {
            console.error('Error loading item details:', error);
            showToast('Failed to load item details', 'error');
        }
    }

    async function saveItem() {
        const id = itemId.value;

        // Create FormData object
        const formData = new FormData(itemForm);

        // Remove empty file input if no file was selected
        if (formData.get('image').size === 0) {
            formData.delete('image');
        }

        try {
            saveItemBtn.disabled = true;
            saveItemBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            let result;

            if (id) {
                // Update existing item
                result = await itemService.updateItem(id, formData);

                if (result) {
                    showToast('Item updated successfully', 'success');
                } else {
                    showToast('Failed to update item', 'error');
                }
            } else {
                // Create new item
                result = await itemService.createItem(formData);

                if (result) {
                    showToast('Item added successfully', 'success');
                } else {
                    showToast('Failed to add item', 'error');
                }
            }

            closeModal(itemModal);
            loadItems();
        } catch (error) {
            console.error('Error saving item:', error);
            showToast('Failed to save item', 'error');
        } finally {
            saveItemBtn.disabled = false;
            saveItemBtn.innerHTML = 'Save Item';
        }
    }

    function confirmArchiveItem(id) {
        currentAction = 'archive';
        currentItemId = id;

        confirmationTitle.textContent = 'Archive Item';
        confirmationMessage.textContent = 'Are you sure you want to archive this item? It will no longer be available for use.';

        openModal(confirmationModal);
    }

    async function archiveItem(id) {
        try {
            const result = await itemService.archiveItem(id);

            if (result && result.success) {
                showToast('Item archived successfully', 'success');
                loadItems();
            } else {
                showToast('Failed to archive item', 'error');
            }
        } catch (error) {
            console.error('Error archiving item:', error);
            showToast('Failed to archive item', 'error');
        }
    }

    function confirmRestoreItem(id) {
        currentAction = 'restore';
        currentItemId = id;

        confirmationTitle.textContent = 'Restore Item';
        confirmationMessage.textContent = 'Are you sure you want to restore this item? It will be available for use again.';

        openModal(confirmationModal);
    }

    async function restoreItem(id) {
        try {
            const result = await itemService.restoreItem(id);

            if (result && result.success) {
                showToast('Item restored successfully', 'success');
                loadItems();
            } else {
                showToast('Failed to restore item', 'error');
            }
        } catch (error) {
            console.error('Error restoring item:', error);
            showToast('Failed to restore item', 'error');
        }
    }

    function confirmDeleteItem(id) {
        currentAction = 'delete';
        currentItemId = id;

        confirmationTitle.textContent = 'Delete Item';
        confirmationMessage.textContent = 'Are you sure you want to delete this item? This action cannot be undone.';

        openModal(confirmationModal);
    }

    async function deleteItem(id) {
        try {
            const result = await itemService.deleteItem(id);

            if (result && result.success) {
                showToast('Item deleted successfully', 'success');
                loadItems();
            } else {
                showToast('Failed to delete item', 'error');
            }
        } catch (error) {
            console.error('Error deleting item:', error);
            showToast('Failed to delete item', 'error');
        }
    }

    function showLoading() {
        itemsGrid.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <p>Loading items...</p>
            </div>
        `;
    }

    function showError(message) {
        itemsGrid.innerHTML = `
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
            loadItems();
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

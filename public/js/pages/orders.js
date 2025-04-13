
import orderService from '../services/order';
import orderItemService from '../services/orders-items';
import customerService from '../services/customers';
import serviceService from '../services/service';
import itemService from '../services/items';

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const ordersTableBody = document.getElementById('ordersTableBody');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const serviceFilter = document.getElementById('serviceFilter');
    const archivedFilter = document.getElementById('archivedFilter');
    const addOrderBtn = document.getElementById('addOrderBtn');
    const orderModal = document.getElementById('orderModal');
    const orderForm = document.getElementById('orderForm');
    const modalTitle = document.getElementById('modalTitle');
    const orderId =   document.getElementById('orderId');
    const customerId = document.getElementById('customerId');
    const serviceId = document.getElementById('serviceId');
    const status = document.getElementById('status');
    const saveOrderBtn = document.getElementById('saveOrderBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');

    // Order Items Modal Elements
    const orderItemsModal = document.getElementById('orderItemsModal');
    const itemsModalTitle = document.getElementById('itemsModalTitle');
    const orderNumber = document.getElementById('orderNumber');
    const orderCustomer = document.getElementById('orderCustomer');
    const orderServiceElement = document.getElementById('orderService');
    const orderStatus = document.getElementById('orderStatus');
    const orderItemsTableBody = document.getElementById('orderItemsTableBody');
    const orderItemsTotal = document.getElementById('orderItemsTotal');
    const addItemBtn = document.getElementById('addItemBtn');
    const closeItemsModalBtn = document.getElementById('closeItemsModalBtn');
    const closeItemsBtn = document.getElementById('closeItemsBtn');

    // Add Item Modal Elements
    const addItemModal = document.getElementById('addItemModal');
    const addItemForm = document.getElementById('addItemForm');
    const currentOrderIdElement = document.getElementById('currentOrderId');
    const itemId = document.getElementById('itemId');
    const itemQuantity = document.getElementById('itemQuantity');
    const itemAmount = document.getElementById('itemAmount');
    const saveItemBtn = document.getElementById('saveItemBtn');
    const cancelAddItemBtn = document.getElementById('cancelAddItemBtn');
    const closeAddItemModalBtn = document.getElementById('closeAddItemModalBtn');

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
    let totalOrders = 0;
    let currentAction = null;
    let orderItemId = null;
    let searchTimeout = null;
    let customers = [];
    let services = [];
    let items = [];
    let currentOrderItems = [];

    // Services
    const orderService = new OrderService();
    const orderItemService = new OrderItemService();
    const customerService = new CustomerService();
    const serviceService = new ServiceService();
    const itemService = new ItemService();

    // Load data on page load
    loadOrders();
    loadCustomers();
    loadServices();
    loadItems();

    // Event listeners
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadOrders();
        }, 500);
    });

    statusFilter.addEventListener('change', function() {
        currentPage = 1;
        loadOrders();
    });

    serviceFilter.addEventListener('change', function() {
        currentPage = 1;
        loadOrders();
    });

    archivedFilter.addEventListener('change', function() {
        currentPage = 1;
        loadOrders();
    });

    addOrderBtn.addEventListener('click', function() {
        openAddOrderModal();
    });

    orderForm.addEventListener('submit', function(e) {
        e.preventDefault();
        saveOrder();
    });

    cancelBtn.addEventListener('click', function() {
        closeModal(orderModal);
    });

    closeModalBtn.addEventListener('click', function() {
        closeModal(orderModal);
    });

    closeItemsModalBtn.addEventListener('click', function() {
        closeModal(orderItemsModal);
    });

    closeItemsBtn.addEventListener('click', function() {
        closeModal(orderItemsModal);
    });

    addItemBtn.addEventListener('click', function() {
        openAddItemModal();
    });

    addItemForm.addEventListener('submit', function(e) {
        e.preventDefault();
        saveOrderItem();
    });

    cancelAddItemBtn.addEventListener('click', function() {
        closeModal(addItemModal);
    });

    closeAddItemModalBtn.addEventListener('click', function() {
        closeModal(addItemModal);
    });

    cancelConfirmationBtn.addEventListener('click', function() {
        closeModal(confirmationModal);
    });

    closeConfirmationBtn.addEventListener('click', function() {
        closeModal(confirmationModal);
    });

    confirmActionBtn.addEventListener('click', function() {
        if (currentAction === 'delete-order') {
            deleteOrder(orderItemId);
        } else if (currentAction === 'archive-order') {
            archiveOrder(orderItemId);
        } else if (currentAction === 'restore-order') {
            restoreOrder(orderItemId);
        } else if (currentAction === 'delete-item') {
            deleteOrderItem(orderItemId);
        }

        closeModal(confirmationModal);
    });

    prevPageBtn.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            loadOrders();
        }
    });

    nextPageBtn.addEventListener('click', function() {
        if (currentPage < totalPages) {
            currentPage++;
            loadOrders();
        }
    });

    // Show item price when item is selected
    itemId.addEventListener('change', function() {
        const selectedItem = items.find(item => item.id == this.value);
        if (selectedItem) {
            itemAmount.value = selectedItem.amount;
        } else {
            itemAmount.value = '';
        }
    });

    // Functions

    async function loadOrders() {
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

            // Add status filter if selected
            if (statusFilter.value) {
                params.status = statusFilter.value;
            }

            // Add service filter if selected
            if (serviceFilter.value) {
                params.service_id = serviceFilter.value;
            }

            // Add archived filter if selected
            if (archivedFilter.value !== '') {
                params.archived = archivedFilter.value;
            }

            const result = await orderService.getOrders(params);

            if (result) {
                renderOrders(result.data);
                updatePagination(result.meta);
            } else {
                showError('Failed to load orders');
            }
        } catch (error) {
            console.error('Error loading orders:', error);
            showError('Failed to load orders');
        }
    }

    async function loadCustomers() {
        try {
            const result = await customerService.getCustomers({ per_page: 100 });

            if (result && result.data) {
                customers = result.data;

                // Populate customer select
                customerId.innerHTML = '<option value="">Select Customer</option>';
                customers.forEach(customer => {
                    const option = document.createElement('option');
                    option.value = customer.id;
                    option.textContent = `${customer.first_name} ${customer.last_name}`;
                    customerId.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading customers:', error);
            showToast('Failed to load customers', 'error');
        }
    }

    async function loadServices() {
        try {
            const result = await serviceService.getServices({ per_page: 100 });

            if (result && result.data) {
                services = result.data;

                // Populate service filter
                serviceFilter.innerHTML = '<option value="">All Services</option>';
                services.forEach(service => {
                    const option = document.createElement('option');
                    option.value = service.id;
                    option.textContent = service.name;
                    serviceFilter.appendChild(option);
                });

                // Populate service select
                serviceId.innerHTML = '<option value="">Select Service</option>';
                services.forEach(service => {
                    const option = document.createElement('option');
                    option.value = service.id;
                    option.textContent = service.name;
                    serviceId.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading services:', error);
            showToast('Failed to load services', 'error');
        }
    }

    async function loadItems() {
        try {
            const result = await itemService.getItems({ per_page: 100, archived: false });

            if (result && result.data) {
                items = result.data;

                // Populate item select
                itemId.innerHTML = '<option value="">Select Item</option>';
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.name} (₦${parseFloat(item.amount).toFixed(2)})`;
                    itemId.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading items:', error);
            showToast('Failed to load items', 'error');
        }
    }

    function renderOrders(orders) {
        ordersTableBody.innerHTML = '';

        if (!orders || orders.length === 0) {
            ordersTableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="empty-cell">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h3 class="empty-state-title">No Orders Found</h3>
                            <p class="empty-state-description">
                                ${searchInput.value.trim() || statusFilter.value || serviceFilter.value || archivedFilter.value !== ''
                                    ? 'No orders match your current filters. Try adjusting your search criteria.'
                                    : 'Start by creating your first order.'}
                            </p>
                            <button id="emptyStateAddBtn" class="btn btn-primary">
                                <i class="fas fa-plus"></i> New Order
                            </button>
                        </div>
                    </td>
                </tr>
            `;

            document.getElementById('emptyStateAddBtn').addEventListener('click', function() {
                openAddOrderModal();
            });

            return;
        }

        orders.forEach(order => {
            const row = document.createElement('tr');

            // Format date
            const createdDate = new Date(order.created_at);
            const formattedDate = createdDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });

            // Get customer name
            const customerName = order.customer
                ? `${order.customer.first_name} ${order.customer.last_name}`
                : 'Unknown Customer';

            // Get service name
            const serviceName = services.find(s => s.id === order.service_id)?.name || 'Unknown Service';

            row.innerHTML = `
                <td>#${order.id}</td>
                <td>${customerName}</td>
                <td>${serviceName}</td>
                <td>
                    <span class="status-badge ${order.status}">
                        ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                    </span>
                </td>
                <td>${formattedDate}</td>
                <td>
                    <button class="btn-icon view-items-btn" data-id="${order.id}" title="View Items">
                        <span class="item-count">0</span>
                    </button>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-icon edit-btn" data-id="${order.id}" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        ${order.archived ?
                            `<button class="btn-icon restore-btn" data-id="${order.id}" title="Restore">
                                <i class="fas fa-undo"></i>
                            </button>` :
                            `<button class="btn-icon archive-btn" data-id="${order.id}" title="Archive">
                                <i class="fas fa-archive"></i>
                            </button>`
                        }
                        <button class="btn-icon delete-btn" data-id="${order.id}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;

            ordersTableBody.appendChild(row);
        });

        // Add event listeners to action buttons
        document.querySelectorAll('.view-items-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openOrderItemsModal(id);
            });
        });

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openEditOrderModal(id);
            });
        });

        document.querySelectorAll('.archive-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                confirmArchiveOrder(id);
            });
        });

        document.querySelectorAll('.restore-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                confirmRestoreOrder(id);
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                confirmDeleteOrder(id);
            });
        });
    }

    function updatePagination(meta) {
        if (!meta) return;

        totalOrders = meta.total;
        currentPage = meta.current_page;
        totalPages = meta.last_page;
        perPage = meta.per_page;

        const start = (currentPage - 1) * perPage + 1;
        const end = Math.min(start + perPage - 1, totalOrders);

        paginationStart.textContent = totalOrders > 0 ? start : 0;
        paginationEnd.textContent = end;
        paginationTotal.textContent = totalOrders;

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
                loadOrders();
            });

            paginationPages.appendChild(pageBtn);
        }
    }

    function openAddOrderModal() {
        modalTitle.textContent = 'New Order';
        orderId.value = '';
        orderForm.reset();

        openModal(orderModal);
    }

    async function openEditOrderModal(id) {
        modalTitle.textContent = 'Edit Order';
        orderId.value = id;
        orderForm.reset();

        try {
            const order = await orderService.getOrder(id);

            if (order) {
                // Fill form with order data
                customerId.value = order.customer_id;
                serviceId.value = order.service_id;
                status.value = order.status;

                openModal(orderModal);
            } else {
                showToast('Order not found', 'error');
            }
        } catch (error) {
            console.error('Error loading order for edit:', error);
            showToast('Failed to load order details', 'error');
        }
    }

    async function openOrderItemsModal(id) {
        try {
            const order = await orderService.getOrder(id);

            if (order) {
                // Set current order ID for adding items
                currentOrderIdElement.value = order.id;

                // Fill order info
                itemsModalTitle.textContent = `Order #${order.id} Items`;
                orderNumber.textContent = `#${order.id}`;

                const customerName = order.customer
                    ? `${order.customer.first_name} ${order.customer.last_name}`
                    : 'Unknown Customer';
                orderCustomer.textContent = customerName;

                const serviceName = services.find(s => s.id === order.service_id)?.name || 'Unknown Service';
                orderServiceElement.textContent = serviceName;

                orderStatus.textContent = order.status.charAt(0).toUpperCase() + order.status.slice(1);
                orderStatus.className = order.status;

                // Load order items
                loadOrderItems(order.id);

                openModal(orderItemsModal);
            } else {
                showToast('Order not found', 'error');
            }
        } catch (error) {
            console.error('Error loading order details:', error);
            showToast('Failed to load order details', 'error');
        }
    }

    async function loadOrderItems(orderId) {
        orderItemsTableBody.innerHTML = `
            <tr>
                <td colspan="5" class="loading-cell">
                    <div class="loading-container">
                        <div class="loading-spinner"></div>
                        <p>Loading items...</p>
                    </div>
                </td>
            </tr>
        `;

        try {
            const result = await orderItemService.getOrderItems({ order_id: orderId });

            if (result) {
                currentOrderItems = result.data;
                renderOrderItems(result.data);
            } else {
                orderItemsTableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="empty-cell">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <h3 class="empty-state-title">No Items Found</h3>
                                <p class="empty-state-description">
                                    This order doesn't have any items yet.
                                </p>
                                <button id="emptyStateAddItemBtn" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                        </td>
                    </tr>
                `;

                document.getElementById('emptyStateAddItemBtn').addEventListener('click', function() {
                    openAddItemModal();
                });

                orderItemsTotal.textContent = '₦0.00';
            }
        } catch (error) {
            console.error('Error loading order items:', error);
            orderItemsTableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="empty-cell">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <h3 class="empty-state-title">Error</h3>
                            <p class="empty-state-description">
                                Failed to load order items.
                            </p>
                            <button id="retryLoadItemsBtn" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Retry
                            </button>
                        </div>
                    </td>
                </tr>
            `;

            document.getElementById('retryLoadItemsBtn').addEventListener('click', function() {
                loadOrderItems(orderId);
            });
        }
    }

    function renderOrderItems(orderItems) {
        orderItemsTableBody.innerHTML = '';

        if (!orderItems || orderItems.length === 0) {
            orderItemsTableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="empty-cell">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <h3 class="empty-state-title">No Items Found</h3>
                            <p class="empty-state-description">
                                This order doesn't have any items yet.
                            </p>
                            <button id="emptyStateAddItemBtn" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </div>
                    </td>
                </tr>
            `;

            document.getElementById('emptyStateAddItemBtn').addEventListener('click', function() {
                openAddItemModal();
            });

            orderItemsTotal.textContent = '₦0.00';
            return;
        }

        let total = 0;

        orderItems.forEach(orderItem => {
            const row = document.createElement('tr');

            const itemName = orderItem.item ? orderItem.item.name : 'Unknown Item';
            const itemPrice = parseFloat(orderItem.amount);
            const quantity = parseInt(orderItem.quantity);
            const itemTotal = itemPrice * quantity;

            total += itemTotal;

            row.innerHTML = `
                <td>${itemName}</td>
                <td class="text-right">₦${itemPrice.toFixed(2)}</td>
                <td class="text-center">${quantity}</td>
                <td class="text-right">₦${itemTotal.toFixed(2)}</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-icon delete-item-btn" data-id="${orderItem.id}" title="Remove Item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;

            orderItemsTableBody.appendChild(row);
        });

        // Update total
        orderItemsTotal.textContent = `₦${total.toFixed(2)}`;

        // Add event listeners to action buttons
        document.querySelectorAll('.delete-item-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                confirmDeleteOrderItem(id);
            });
        });

        // Update item count badges in the orders table
        const orderId = currentOrderIdElement.value;
        const itemCountBadge = document.querySelector(`.view-items-btn[data-id="${orderId}"] .item-count`);
        if (itemCountBadge) {
            itemCountBadge.textContent = orderItems.length;
        }
    }

    function openAddItemModal() {
        addItemForm.reset();
        itemAmount.value = '';

        openModal(addItemModal);
    }

    async function saveOrder() {
        const id = orderId.value;

        // Get form data
        const formData = {
            customer_id: customerId.value,
            service_id: serviceId.value,
            status: status.value
        };

        try {
            saveOrderBtn.disabled = true;
            saveOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            let result;

            if (id) {
                // Update existing order
                result = await orderService.updateOrder(id, formData);

                if (result) {
                    showToast('Order updated successfully', 'success');
                } else {
                    showToast('Failed to update order', 'error');
                }
            } else {
                // Create new order
                result = await orderService.createOrder(formData);

                if (result) {
                    showToast('Order created successfully', 'success');
                } else {
                    showToast('Failed to create order', 'error');
                }
            }

            closeModal(orderModal);
            loadOrders();
        } catch (error) {
            console.error('Error saving order:', error);
            showToast('Failed to save order', 'error');
        } finally {
            saveOrderBtn.disabled = false;
            saveOrderBtn.innerHTML = 'Save Order';
        }
    }

    async function saveOrderItem() {
        const orderId = currentOrderIdElement.value;

        // Get form data
        const formData = {
            order_id: orderId,
            items: [{
                item_id: itemId.value,
                quantity: itemQuantity.value,
                amount: itemAmount.value
            }]
        };

        try {
            saveItemBtn.disabled = true;
            saveItemBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

            const result = await orderItemService.addOrderItems(formData);

            if (result) {
                showToast('Item added successfully', 'success');
                closeModal(addItemModal);
                loadOrderItems(orderId);
            } else {
                showToast('Failed to add item', 'error');
            }
        } catch (error) {
            console.error('Error adding item:', error);
            showToast('Failed to add item', 'error');
        } finally {
            saveItemBtn.disabled = false;
            saveItemBtn.innerHTML = 'Add Item';
        }
    }

    function confirmDeleteOrder(id) {
        currentAction = 'delete-order';
        orderItemId = id;

        confirmationTitle.textContent = 'Delete Order';
        confirmationMessage.textContent = 'Are you sure you want to delete this order? This action cannot be undone.';

        openModal(confirmationModal);
    }

    async function deleteOrder(id) {
        try {
            const result = await orderService.deleteOrder(id);

            if (result && result.success) {
                showToast('Order deleted successfully', 'success');
                loadOrders();
            } else {
                showToast('Failed to delete order', 'error');
            }
        } catch (error) {
            console.error('Error deleting order:', error);
            showToast('Failed to delete order', 'error');
        }
    }

    function confirmArchiveOrder(id) {
        currentAction = 'archive-order';
        orderItemId = id;

        confirmationTitle.textContent = 'Archive Order';
        confirmationMessage.textContent = 'Are you sure you want to archive this order?';

        openModal(confirmationModal);
    }

    async function archiveOrder(id) {
        try {
            const result = await orderService.archiveOrder(id);

            if (result && result.success) {
                showToast('Order archived successfully', 'success');
                loadOrders();
            } else {
                showToast('Failed to archive order', 'error');
            }
        } catch (error) {
            console.error('Error archiving order:', error);
            showToast('Failed to archive order', 'error');
        }
    }

    function confirmRestoreOrder(id) {
        currentAction = 'restore-order';
        orderItemId = id;

        confirmationTitle.textContent = 'Restore Order';
        confirmationMessage.textContent = 'Are you sure you want to restore this order?';

        openModal(confirmationModal);
    }

    async function restoreOrder(id) {
        try {
            const result = await orderService.restoreOrder(id);

            if (result && result.success) {
                showToast('Order restored successfully', 'success');
                loadOrders();
            } else {
                showToast('Failed to restore order', 'error');
            }
        } catch (error) {
            console.error('Error restoring order:', error);
            showToast('Failed to restore order', 'error');
        }
    }

    function confirmDeleteOrderItem(id) {
        currentAction = 'delete-item';
        orderItemId = id;

        confirmationTitle.textContent = 'Remove Item';
        confirmationMessage.textContent = 'Are you sure you want to remove this item from the order?';

        openModal(confirmationModal);
    }

    async function deleteOrderItem(id) {
        try {
            const result = await orderItemService.deleteOrderItem(id);

            if (result && result.success) {
                showToast('Item removed successfully', 'success');
                loadOrderItems(currentOrderIdElement.value);
            } else {
                showToast('Failed to remove item', 'error');
            }
        } catch (error) {
            console.error('Error removing item:', error);
            showToast('Failed to remove item', 'error');
        }
    }

    function showLoading() {
        ordersTableBody.innerHTML = `
            <tr>
                <td colspan="7" class="loading-cell">
                    <div class="loading-container">
                        <div class="loading-spinner"></div>
                        <p>Loading orders...</p>
                    </div>
                </td>
            </tr>
        `;
    }

    function showError(message) {
        ordersTableBody.innerHTML = `
            <tr>
                <td colspan="7" class="empty-cell">
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
            loadOrders();
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

        const toastContainer =   document.getElementById('toastContainer');
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

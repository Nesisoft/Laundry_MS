<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | Laundry Management System</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/orders.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar (include from a common file or component) -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-title">Laundry App</h1>
                <button id="toggleSidebar" class="sidebar-toggle">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="sidebar-divider"></div>
            <nav class="sidebar-nav">
                <a href="../dashboard.html" class="sidebar-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Dashboard</span>
                </a>
                <a href="./index.html" class="sidebar-link active">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="../customers/index.html" class="sidebar-link">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
                <a href="../services/index.html" class="sidebar-link">
                    <i class="fas fa-concierge-bell"></i>
                    <span>Services</span>
                </a>
                <a href="../items/index.html" class="sidebar-link">
                    <i class="fas fa-box"></i>
                    <span>Items</span>
                </a>
                <a href="../employees/index.html" class="sidebar-link">
                    <i class="fas fa-user-tie"></i>
                    <span>Employees</span>
                </a>
                <a href="../pickups/index.html" class="sidebar-link">
                    <i class="fas fa-truck-pickup"></i>
                    <span>Pickups</span>
                </a>
                <a href="../deliveries/index.html" class="sidebar-link">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="../invoices/index.html" class="sidebar-link">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Invoices</span>
                </a>
                <a href="../discounts/index.html" class="sidebar-link">
                    <i class="fas fa-tags"></i>
                    <span>Discounts</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="../settings/index.html" class="sidebar-link">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="../login.html" class="sidebar-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-content">
                    <div class="page-header">
                        <h1>Orders</h1>
                    </div>
                    <div class="header-actions">
                        <div class="notification-bell">
                            <i class="fas fa-bell"></i>
                            <span class="notification-indicator"></span>
                        </div>
                        <div class="header-divider"></div>
                        <div class="user-dropdown">
                            <button class="user-dropdown-button" id="userDropdownButton">
                                <div class="avatar">
                                    <span>AD</span>
                                </div>
                                <div class="user-info">
                                    <span class="user-name">Admin User</span>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu" id="userDropdownMenu">
                                <div class="dropdown-header">My Account</div>
                                <div class="dropdown-divider"></div>
                                <a href="../profile.html" class="dropdown-item">
                                    <i class="fas fa-user"></i>
                                    <span>Profile</span>
                                </a>
                                <a href="../settings/index.html" class="dropdown-item">
                                    <i class="fas fa-cog"></i>
                                    <span>Settings</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="../login.html" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Log out</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Orders Content -->
            <div class="content-wrapper">
                <!-- Filters and Actions -->
                <div class="filters-container">
                    <div class="search-container">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Search orders..." class="search-input">
                        </div>
                    </div>
                    <div class="filters">
                        <div class="filter-group">
                            <label for="statusFilter">Status:</label>
                            <select id="statusFilter" class="filter-select">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="serviceFilter">Service:</label>
                            <select id="serviceFilter" class="filter-select">
                                <option value="">All Services</option>
                                <!-- Will be populated dynamically -->
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="archivedFilter">Show:</label>
                            <select id="archivedFilter" class="filter-select">
                                <option value="false">Active Orders</option>
                                <option value="true">Archived Orders</option>
                                <option value="">All Orders</option>
                            </select>
                        </div>
                    </div>
                    <div class="actions">
                        <button id="addOrderBtn" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Order
                        </button>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                            <!-- Will be populated by JavaScript -->
                            <tr>
                                <td colspan="7" class="loading-cell">
                                    <div class="loading-container">
                                        <div class="loading-spinner"></div>
                                        <p>Loading orders...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-container" id="paginationContainer">
                    <div class="pagination-info">
                        Showing <span id="paginationStart">0</span> to <span id="paginationEnd">0</span> of <span id="paginationTotal">0</span> orders
                    </div>
                    <div class="pagination-controls">
                        <button id="prevPageBtn" class="pagination-btn" disabled>
                            <i class="fas fa-chevron-left"></i> Previous
                        </button>
                        <div id="paginationPages" class="pagination-pages">
                            <!-- Page numbers will be populated by JavaScript -->
                        </div>
                        <button id="nextPageBtn" class="pagination-btn" disabled>
                            Next <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Order Modal -->
    <div class="modal" id="orderModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">New Order</h2>
                <button class="close-btn" id="closeModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="orderForm">
                    <input type="hidden" id="orderId">

                    <div class="form-group">
                        <label for="customerId">Customer <span class="required">*</span></label>
                        <select id="customerId" name="customer_id" required>
                            <option value="">Select Customer</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="serviceId">Service <span class="required">*</span></label>
                        <select id="serviceId" name="service_id" required>
                            <option value="">Select Service</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status <span class="required">*</span></label>
                        <select id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                        <button type="submit" id="saveOrderBtn" class="btn btn-primary">Save Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Order Items Modal -->
    <div class="modal" id="orderItemsModal">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2 id="itemsModalTitle">Order Items</h2>
                <button class="close-btn" id="closeItemsModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="order-info-header">
                    <div class="order-info-item">
                        <span class="order-info-label">Order #:</span>
                        <span id="orderNumber" class="order-info-value">-</span>
                    </div>
                    <div class="order-info-item">
                        <span class="order-info-label">Customer:</span>
                        <span id="orderCustomer" class="order-info-value">-</span>
                    </div>
                    <div class="order-info-item">
                        <span class="order-info-label">Service:</span>
                        <span id="orderService" class="order-info-value">-</span>
                    </div>
                    <div class="order-info-item">
                        <span class="order-info-label">Status:</span>
                        <span id="orderStatus" class="order-info-value">-</span>
                    </div>
                </div>

                <div class="order-items-container">
                    <div class="order-items-header">
                        <h3>Items</h3>
                        <button id="addItemBtn" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="orderItemsTableBody">
                                <!-- Will be populated by JavaScript -->
                                <tr>
                                    <td colspan="5" class="loading-cell">
                                        <div class="loading-container">
                                            <div class="loading-spinner"></div>
                                            <p>Loading items...</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                    <td id="orderItemsTotal" class="text-right">₦0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeItemsBtn" class="btn btn-secondary">Close</button>
            </div>
        </div>
    </div>

    <!-- Add Order Item Modal -->
    <div class="modal" id="addItemModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="addItemModalTitle">Add Item to Order</h2>
                <button class="close-btn" id="closeAddItemModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="addItemForm">
                    <input type="hidden" id="currentOrderId">

                    <div class="form-group">
                        <label for="itemId">Item <span class="required">*</span></label>
                        <select id="itemId" name="item_id" required>
                            <option value="">Select Item</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="itemQuantity">Quantity <span class="required">*</span></label>
                        <input type="number" id="itemQuantity" name="quantity" min="1" value="1" required>
                    </div>

                    <div class="form-group">
                        <label for="itemAmount">Price (₦)</label>
                        <input type="number" id="itemAmount" name="amount" min="0" step="0.01" readonly>
                        <small class="form-text">Price will be automatically filled based on the selected item.</small>
                    </div>

                    <div class="form-actions">
                        <button type="button" id="cancelAddItemBtn" class="btn btn-secondary">Cancel</button>
                        <button type="submit" id="saveItemBtn" class="btn btn-primary">Add Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmationModal">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h2 id="confirmationTitle">Confirm Action</h2>
                <button class="close-btn" id="closeConfirmationBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p id="confirmationMessage">Are you sure you want to perform this action?</p>
                <div class="form-actions">
                    <button type="button" id="cancelConfirmationBtn" class="btn btn-secondary">Cancel</button>
                    <button type="button" id="confirmActionBtn" class="btn btn-danger">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Toast Notification -->
    <div class="toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
    <script src="{{ asset('js/services/customers.js') }}"></script>
    <script src="{{ asset('js/pages/customers.js') }}"></script>
@endsection

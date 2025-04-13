<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Discounts | Laundry Management System</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/customer-discounts.css">
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
                <a href="../orders/index.html" class="sidebar-link">
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
                <a href="./index.html" class="sidebar-link active">
                    <i class="fas fa-user-tag"></i>
                    <span>Customer Discounts</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="../settings.html" class="sidebar-link">
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
                        <h1>Customer Discounts</h1>
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
                                <a href="../settings.html" class="dropdown-item">
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

            <!-- Customer Discount Content -->
            <div class="content-wrapper">
                <!-- Filters and Actions -->
                <div class="filters-container">
                    <div class="filters">
                        <div class="filter-group">
                            <label for="customerFilter">Customer:</label>
                            <select id="customerFilter" class="filter-select">
                                <option value="">All Customers</option>
                                <!-- Will be populated by JavaScript -->
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="discountFilter">Discount:</label>
                            <select id="discountFilter" class="filter-select">
                                <option value="">All Discounts</option>
                                <!-- Will be populated by JavaScript -->
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="statusFilter">Status:</label>
                            <select id="statusFilter" class="filter-select">
                                <option value="active">Active</option>
                                <option value="expired">Expired</option>
                                <option value="">All</option>
                            </select>
                        </div>
                    </div>
                    <div class="actions">
                        <button id="addCustomerDiscountBtn" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Assign Discount
                        </button>
                    </div>
                </div>

                <!-- Customer Discounts Table -->
                <div class="table-container">
                    <table class="data-table" id="customerDiscountsTable">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Discount</th>
                                <th>Value</th>
                                <th>Expiration Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customerDiscountsTableBody">
                            <!-- Will be populated by JavaScript -->
                            <tr>
                                <td colspan="6" class="loading-cell">
                                    <div class="loading-container">
                                        <div class="loading-spinner"></div>
                                        <p>Loading customer discounts...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-container" id="paginationContainer">
                    <div class="pagination-info">
                        Showing <span id="paginationStart">0</span> to <span id="paginationEnd">0</span> of <span id="paginationTotal">0</span> records
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

    <!-- Add/Edit Customer Discount Modal -->
    <div class="modal" id="customerDiscountModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Assign Discount to Customer</h2>
                <button class="close-btn" id="closeModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="customerDiscountForm">
                    <input type="hidden" id="customerDiscountId">

                    <div class="form-group">
                        <label for="customerSelect">Customer <span class="required">*</span></label>
                        <select id="customerSelect" name="customer_id" required>
                            <option value="">Select Customer</option>
                            <!-- Will be populated by JavaScript -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="discountSelect">Discount <span class="required">*</span></label>
                        <select id="discountSelect" name="discount_id" required>
                            <option value="">Select Discount</option>
                            <!-- Will be populated by JavaScript -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="customerExpirationDate">Customer-Specific Expiration Date</label>
                        <input type="date" id="customerExpirationDate" name="customer_expiration_date">
                        <small>Leave blank to use the discount's default expiration date</small>
                    </div>

                    <div class="form-group" id="discountInfoContainer">
                        <!-- Discount info will be shown here when a discount is selected -->
                    </div>

                    <div class="form-actions">
                        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                        <button type="submit" id="saveCustomerDiscountBtn" class="btn btn-primary">Assign Discount</button>
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

    <script src="../js/models/customer-discount.js"></script>
    <script src="../js/services/api-service.js"></script>
    <script src="../js/services/customer-service.js"></script>
    <script src="../js/services/discount-service.js"></script>
    <script src="../js/services/customer-discount-service.js"></script>
    <script src="../js/ui/sidebar.js"></script>
    <script src="../js/ui/dropdown.js"></script>
    <script src="../js/ui/toast.js"></script>
    <script src="../js/pages/customer-discounts.js"></script>
</body>
</html>

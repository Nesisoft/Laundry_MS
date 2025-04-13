<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items | Laundry Management System</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/items.css">
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
                    <i class="fas fa-box"></i>
                    <span>Items</span>
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
                        <h1>Items</h1>
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

            <!-- Item Content -->
            <div class="content-wrapper">
                <!-- Filters and Actions -->
                <div class="filters-container">
                    <div class="search-container">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Search items..." class="search-input">
                        </div>
                    </div>
                    <div class="filters">
                        <div class="filter-group">
                            <label for="categoryFilter">Category:</label>
                            <select id="categoryFilter" class="filter-select">
                                <option value="">All Categories</option>
                                <!-- Will be populated dynamically -->
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="archivedFilter">Status:</label>
                            <select id="archivedFilter" class="filter-select">
                                <option value="false">Active</option>
                                <option value="true">Archived</option>
                                <option value="">All</option>
                            </select>
                        </div>
                    </div>
                    <div class="actions">
                        <button id="addItemBtn" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                </div>

                <!-- Items Grid -->
                <div class="items-grid" id="itemsGrid">
                    <!-- Will be populated by JavaScript -->
                    <div class="loading-container">
                        <div class="loading-spinner"></div>
                        <p>Loading items...</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="pagination-container" id="paginationContainer">
                    <div class="pagination-info">
                        Showing <span id="paginationStart">0</span> to <span id="paginationEnd">0</span> of <span id="paginationTotal">0</span> items
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

    <!-- Add/Edit Item Modal -->
    <div class="modal" id="itemModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Item</h2>
                <button class="close-btn" id="closeModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="itemForm" enctype="multipart/form-data">
                    <input type="hidden" id="itemId">

                    <div class="form-group">
                        <label for="name">Item Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="amount">Price (₦) <span class="required">*</span></label>
                        <input type="number" id="amount" name="amount" min="0" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="">Select Category</option>
                            <option value="laundry">Laundry</option>
                            <option value="cleaning">Cleaning</option>
                            <option value="supplies">Supplies</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="image">Item Image</label>
                        <div class="image-upload-container">
                            <div class="image-preview" id="imagePreview">
                                <img id="previewImg" src="../images/placeholder-image.png" alt="Preview">
                            </div>
                            <div class="image-upload-controls">
                                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg">
                                <small>Supported formats: JPG, JPEG, PNG (max 2MB)</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                        <button type="submit" id="saveItemBtn" class="btn btn-primary">Save Item</button>
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

@endsection

@section('scripts')
    <script src="{{ asset('js/services/customers.js') }}"></script>
    <script src="{{ asset('js/pages/customers.js') }}"></script>
@endsection

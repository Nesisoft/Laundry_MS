<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details | Laundry Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customers.css') }}">
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
                <a href="./index.html" class="sidebar-link active">
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
                        <div class="breadcrumb">
                            <a href="./index.html">Customers</a>
                            <i class="fas fa-chevron-right"></i>
                            <span id="customerName">Customer Details</span>
                        </div>
                        <div class="connection-status" id="connectionStatus">
                            <span class="status-indicator offline"></span>
                            <span class="status-text">Offline</span>
                        </div>
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

            <!-- Customer Detail Content -->
            <div class="content-wrapper">
                <div class="customer-detail-header">
                    <div class="customer-info">
                        <div class="customer-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="customer-header-details">
                            <h1 id="customerFullName">Loading...</h1>
                            <div class="customer-meta">
                                <span id="customerPhone"><i class="fas fa-phone"></i> Loading...</span>
                                <span id="customerEmail"><i class="fas fa-envelope"></i> Loading...</span>
                            </div>
                            <div class="customer-status" id="customerStatus">
                                <span class="status-badge active">Active</span>
                            </div>
                        </div>
                    </div>
                    <div class="customer-actions">
                        <button id="editCustomerBtn" class="btn btn-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button id="archiveCustomerBtn" class="btn btn-warning">
                            <i class="fas fa-archive"></i> Archive
                        </button>
                        <button id="deleteCustomerBtn" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>

                <div class="customer-detail-content">
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <h2>Personal Information</h2>
                        </div>
                        <div class="detail-card-content">
                            <div class="detail-row">
                                <div class="detail-label">First Name</div>
                                <div class="detail-value" id="detailFirstName">Loading...</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Last Name</div>
                                <div class="detail-value" id="detailLastName">Loading...</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Phone Number</div>
                                <div class="detail-value" id="detailPhone">Loading...</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Email</div>
                                <div class="detail-value" id="detailEmail">Loading...</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Gender</div>
                                <div class="detail-value" id="detailGender">Loading...</div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-card-header">
                            <h2>Address Information</h2>
                        </div>
                        <div class="detail-card-content" id="addressContent">
                            <div class="detail-row">
                                <div class="detail-label">Street</div>
                                <div class="detail-value" id="detailStreet">Loading...</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">City</div>
                                <div class="detail-value" id="detailCity">Loading...</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">State</div>
                                <div class="detail-value" id="detailState">Loading...</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Zip Code</div>
                                <div class="detail-value" id="detailZipCode">Loading...</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Country</div>
                                <div class="detail-value" id="detailCountry">Loading...</div>
                            </div>
                            <div class="detail-row" id="mapContainer">
                                <div id="addressMap" class="address-map">
                                    <div class="map-placeholder">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <p>Map location will be displayed here if coordinates are available</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-card-header">
                            <h2>Order History</h2>
                            <a href="../orders/index.html?customer_id=1" class="btn btn-sm btn-secondary">View All</a>
                        </div>
                        <div class="detail-card-content">
                            <div class="table-container">
                                <table class="data-table" id="ordersTable">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Items</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ordersTableBody">
                                        <tr class="loading-row">
                                            <td colspan="6" class="text-center">
                                                <div class="loading-spinner"></div>
                                                <p>Loading orders...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Edit Customer Modal -->
    <div class="modal" id="customerModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Edit Customer</h2>
                <button class="close-btn" id="closeModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="customerForm">
                    <input type="hidden" id="customerId">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name <span class="required">*</span></label>
                            <input type="text" id="firstName" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name <span class="required">*</span></label>
                            <input type="text" id="lastName" name="last_name" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number <span class="required">*</span></label>
                            <input type="tel" id="phoneNumber" name="phone_number" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="sex" value="male"> Male
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="sex" value="female"> Female
                            </label>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Address Information</h3>
                        <div class="form-group">
                            <label for="street">Street</label>
                            <input type="text" id="street" name="street">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city">
                            </div>
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" id="state" name="state">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="zipCode">Zip Code</label>
                                <input type="text" id="zipCode" name="zip_code">
                            </div>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" id="country" name="country" value="Nigeria">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <input type="number" id="latitude" name="latitude" step="0.000001">
                            </div>
                            <div class="form-group">
                                <label for="longitude">Longitude</label>
                                <input type="number" id="longitude" name="longitude" step="0.000001">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" id="getLocationBtn" class="btn btn-secondary">
                                <i class="fas fa-map-marker-alt"></i> Get Current Location
                            </button>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                        <button type="submit" id="saveCustomerBtn" class="btn btn-primary">Save Changes</button>
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

    <script src="../js/models/customer.js"></script>
    <script src="../js/services/api-service.js"></script>
    <script src="../js/services/customer-service.js"></script>
    <script src="../js/services/offline-storage.js"></script>
    <script src="../js/ui/sidebar.js"></script>
    <script src="../js/ui/dropdown.js"></script>
    <script src="../js/ui/toast.js"></script>
    <script src="../js/ui/connection-status.js"></script>
    <script src="../js/pages/customer-detail.js"></script>
</body>
</html>

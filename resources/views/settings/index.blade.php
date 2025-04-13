<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Local Configuration | Laundry Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/settings.css') }}">
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
                <a href="./index.html" class="sidebar-link active">
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
                        <h1>Settings</h1>
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
                                <a href="./index.html" class="dropdown-item">
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

            <!-- Settings Content -->
            <div class="content-wrapper">
                <!-- Settings Navigation -->
                <div class="settings-nav">
                    <a href="./index.html" class="settings-nav-item">
                        <i class="fas fa-user"></i>
                        <span>Account</span>
                    </a>
                    <a href="./local-config.html" class="settings-nav-item active">
                        <i class="fas fa-store"></i>
                        <span>Business Information</span>
                    </a>
                    <a href="./security.html" class="settings-nav-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Security</span>
                    </a>
                    <a href="./notifications.html" class="settings-nav-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                    <a href="./appearance.html" class="settings-nav-item">
                        <i class="fas fa-paint-brush"></i>
                        <span>Appearance</span>
                    </a>
                </div>

                <!-- Local Config Content -->
                <div class="settings-content">
                    <div class="settings-header">
                        <h2>Business Information</h2>
                        <p>Configure your business information that will appear on receipts and customer communications.</p>
                    </div>

                    <div class="settings-section">
                        <div class="settings-section-header">
                            <h3>Logo</h3>
                        </div>
                        <div class="settings-section-content">
                            <div class="logo-upload-container">
                                <div class="logo-preview" id="logoPreview">
                                    <img id="logoImg" src="../images/placeholder-logo.png" alt="Business Logo">
                                    <div class="logo-upload-overlay">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                </div>
                                <form id="logoForm" enctype="multipart/form-data">
                                    <input type="file" id="logoInput" name="logo" accept="image/jpeg,image/png,image/jpg" hidden>
                                    <button type="button" id="selectLogoBtn" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-upload"></i> Upload Logo
                                    </button>
                                    <button type="button" id="removeLogoBtn" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </form>
                                <p class="form-text">Recommended size: 200x200 pixels. Max file size: 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <div class="settings-section">
                        <div class="settings-section-header">
                            <h3>Business Details</h3>
                        </div>
                        <div class="settings-section-content">
                            <form id="businessInfoForm">
                                <div class="form-group">
                                    <label for="businessName">Business Name</label>
                                    <input type="text" id="businessName" name="business_name" placeholder="Your Business Name">
                                </div>
                                <div class="form-group">
                                    <label for="branchName">Branch Name</label>
                                    <input type="text" id="branchName" name="branch_name" placeholder="Branch Name (if applicable)">
                                </div>
                                <div class="form-group">
                                    <label for="phoneNumber">Phone Number</label>
                                    <input type="tel" id="phoneNumber" name="phone_number" placeholder="Business Phone Number">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" placeholder="Business Email Address">
                                </div>
                                <div class="form-group">
                                    <label for="motto">Business Motto/Slogan</label>
                                    <input type="text" id="motto" name="motto" placeholder="Your Business Motto or Slogan">
                                </div>
                                <div class="form-actions">
                                    <button type="button" id="resetBtn" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo"></i> Reset to Default
                                    </button>
                                    <button type="submit" id="saveBusinessInfoBtn" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="settings-section">
                        <div class="settings-section-header">
                            <h3>Custom Configuration</h3>
                            <button id="addConfigBtn" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Add Config
                            </button>
                        </div>
                        <div class="settings-section-content">
                            <div class="table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Key</th>
                                            <th>Value</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="configTableBody">
                                        <!-- Will be populated by JavaScript -->
                                        <tr>
                                            <td colspan="3" class="loading-cell">
                                                <div class="loading-container">
                                                    <div class="loading-spinner"></div>
                                                    <p>Loading configurations...</p>
                                                </div>
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

    <!-- Add/Edit Config Modal -->
    <div class="modal" id="configModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="configModalTitle">Add Configuration</h2>
                <button class="close-btn" id="closeConfigModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="configForm">
                    <div class="form-group">
                        <label for="configKey">Key <span class="required">*</span></label>
                        <input type="text" id="configKey" name="key" required>
                    </div>
                    <div class="form-group">
                        <label for="configValue">Value</label>
                        <input type="text" id="configValue" name="value">
                    </div>
                    <div class="form-actions">
                        <button type="button" id="cancelConfigBtn" class="btn btn-secondary">Cancel</button>
                        <button type="submit" id="saveConfigBtn" class="btn btn-primary">Save</button>
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

<!-- resources/views/customers/index.blade.php -->
@extends('layouts.app')

@section('title', 'Customers')

@section('page-title', 'Customers')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/customers.css') }}">
@endsection

@section('content')

    <!-- Filters and Actions -->
    <div class="filters-container">
        <div class="search-container">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search customers..." class="search-input">
            </div>
        </div>
        <div class="filters">
            <div class="filter-group">
                <label for="sexFilter">Gender:</label>
                <select id="sexFilter" class="filter-select">
                    <option value="">All</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
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
            <button id="addCustomerBtn" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Customer
            </button>
            <button id="syncBtn" class="btn btn-secondary">
                <i class="fas fa-sync-alt"></i> Sync
            </button>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="table-container">
        <table class="data-table" id="customersTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="customersTableBody">
                <!-- Customer rows will be populated by JavaScript -->
                <tr class="loading-row">
                    <td colspan="7" class="text-center">
                        <div class="loading-spinner"></div>
                        <p>Loading customers...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container" id="paginationContainer">
        <div class="pagination-info">
            Showing <span id="paginationStart">0</span> to <span id="paginationEnd">0</span> of <span id="paginationTotal">0</span> customers
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

    <!-- Add/Edit Customer Modal -->
    <div class="modal" id="customerModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Customer</h2>
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
                                <input type="radio" name="sex" value="male" checked> Male
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
                        <button type="submit" id="saveCustomerBtn" class="btn btn-primary">Save Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <!-- Customer Details Modal -->
    <div class="modal" id="customerDetailsModal">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2 id="detailsModalTitle">Customer Details</h2>
                <button class="close-btn" id="closeDetailsModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="customer-details">
                    <div class="customer-info">
                        <div class="customer-header">
                            <div class="customer-avatar">
                                <span id="customerInitials">JD</span>
                            </div>
                            <div class="customer-name-info">
                                <h3 id="customerName">John Doe</h3>
                                <div class="customer-status" id="customerStatus">Active</div>
                            </div>
                        </div>

                        <div class="customer-details-grid">
                            <div class="detail-item">
                                <div class="detail-label">Email</div>
                                <div class="detail-value" id="customerEmail">john.doe@example.com</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Phone</div>
                                <div class="detail-value" id="customerPhone">+1234567890</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Address</div>
                                <div class="detail-value" id="customerAddress">123 Main St, City, Country</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Customer Since</div>
                                <div class="detail-value" id="customerSince">Jan 1, 2023</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Total Orders</div>
                                <div class="detail-value" id="customerTotalOrders">5</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Last Order</div>
                                <div class="detail-value" id="customerLastOrder">Mar 15, 2023</div>
                            </div>
                        </div>
                    </div>

                    <div class="customer-actions">
                        <button id="editCustomerBtn" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button id="viewOrdersBtn" class="btn btn-secondary">
                            <i class="fas fa-shopping-cart"></i> View Orders
                        </button>
                        <button id="deactivateCustomerBtn" class="btn btn-outline-danger">
                            <i class="fas fa-user-slash"></i> Deactivate
                        </button>
                    </div>
                </div>
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

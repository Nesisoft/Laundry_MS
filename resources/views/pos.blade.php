<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('title', 'POS')

@section('page-title', 'POS')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/pos.css') }}">
@endsection

@section('content')
    <div class="pos-container">
        <!-- Left Side - Services -->
        <div class="pos-services">
            <div class="pos-header">
                <h1 class="pos-title">Laundry POS</h1>
                <button id="selectCustomerBtn" class="btn btn-outline">
                    <i class="fas fa-user"></i>
                    <span id="selectedCustomerName">Select Customer</span>
                </button>
            </div>

            <!-- Search and Filters -->
            <div class="pos-search-filters">
                <div class="search-container">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="serviceSearch" class="search-input" placeholder="Search services...">
                    </div>
                </div>
                <div class="filter-group">
                    <select id="categoryFilter" class="filter-select">
                        <option value="all">All Categories</option>
                        <option value="laundry">Laundry</option>
                        <option value="dry-cleaning">Dry Cleaning</option>
                        <option value="cleaning">Cleaning</option>
                        <option value="bedding">Bedding</option>
                    </select>
                </div>
            </div>

            <!-- Services Grid -->
            <div class="services-grid" id="servicesGrid">
                <!-- Services will be populated here -->
            </div>
        </div>

        <!-- Right Side - Cart -->
        <div class="pos-cart">

            <!-- Replace the existing cart header section with this -->
            <div class="cart-header">
                <h2 class="cart-title"><i class="fas fa-shopping-cart"></i> Cart</h2>
                <button id="clearCartBtn" class="btn btn-outline btn-sm">Clear All</button>
            </div>

            <!-- Add this customer selection section below the cart header -->
            <div class="customer-selection">
                <div class="customer-search-container">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="cartCustomerSearch" class="search-input" placeholder="Search customers..." autocomplete="off">
                    </div>
                </div>
                <div id="customerDropdown" class="customer-dropdown">
                    <div id="customerDropdownContent" class="customer-dropdown-content">
                        <!-- Customers will be populated here -->
                    </div>
                </div>
                <div id="selectedCustomerInfo" class="selected-customer-info hidden">
                    <!-- Selected customer info will be displayed here -->
                </div>
            </div>

            <div class="cart-items-container" id="cartItemsContainer">
                <div id="emptyCartMessage" class="empty-cart-message">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Your cart is empty. Add services to get started.</p>
                </div>
                <div id="cartItems" class="cart-items">
                    <!-- Cart items will be populated here -->
                </div>
            </div>

            <div class="cart-footer">
                <!-- Discount Selection -->
                <div class="discount-section">
                    <div class="discount-header">
                        <p class="discount-label">Apply Discount</p>
                        <button id="removeDiscountBtn" class="btn-text hidden">Remove</button>
                    </div>
                    <div id="discountSelectContainer">
                        <select id="discountSelect" class="filter-select">
                            <option value="">Select discount</option>
                            <!-- Discount options will be populated here -->
                        </select>
                    </div>
                    <div id="appliedDiscountInfo" class="applied-discount hidden">
                        <!-- Applied discount info will be displayed here -->
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal</span>
                        <span id="subtotalAmount" class="summary-value">₦0.00</span>
                    </div>
                    <div id="discountRow" class="summary-row hidden">
                        <span class="summary-label">Discount</span>
                        <span id="discountAmount" class="summary-value discount-value">-₦0.00</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-row total-row">
                        <span class="summary-label">Total</span>
                        <span id="totalAmount" class="summary-value">₦0.00</span>
                    </div>
                </div>

                <!-- Payment Button -->
                <button id="paymentBtn" class="btn btn-primary btn-lg" disabled>
                    Proceed to Payment
                </button>
                <p id="customerRequiredMessage" class="error-message hidden">
                    Please select a customer to continue
                </p>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <!-- Customer Selection Modal -->
    <div id="customerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Select Customer</h2>
                <button class="close-btn" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="search-container mb-4">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="customerSearch" class="search-input" placeholder="Search customers...">
                    </div>
                </div>
                <div class="customers-list" id="customersList">
                    <!-- Customers will be populated here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Payment</h2>
                <button class="close-btn" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="payment-methods">
                    <h3 class="payment-section-title">Payment Method</h3>
                    <div class="payment-tabs">
                        <div class="payment-tab-headers">
                            <button class="payment-tab-btn active" data-tab="cash">
                                <i class="fas fa-money-bill-wave"></i> Cash
                            </button>
                            <button class="payment-tab-btn" data-tab="card">
                                <i class="fas fa-credit-card"></i> Card
                            </button>
                            <button class="payment-tab-btn" data-tab="transfer">
                                <i class="fas fa-exchange-alt"></i> Transfer
                            </button>
                        </div>
                        <div class="payment-tab-content">
                            <div id="cashTab" class="payment-tab active">
                                <div class="form-group">
                                    <label for="amountPaid">Amount Received</label>
                                    <input type="number" id="amountPaid" class="form-control" placeholder="Enter amount">
                                </div>
                                <div id="changeContainer" class="change-container hidden">
                                    <div class="change-info">
                                        <span class="change-label">Change</span>
                                        <span id="changeAmount" class="change-value">₦0.00</span>
                                    </div>
                                </div>
                            </div>
                            <div id="cardTab" class="payment-tab">
                                <p class="payment-info-text">Process card payment using POS terminal</p>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Transaction Reference (Optional)">
                                </div>
                            </div>
                            <div id="transferTab" class="payment-tab">
                                <p class="payment-info-text">Bank Transfer Details</p>
                                <div class="bank-details">
                                    <div class="bank-detail-row">
                                        <span class="bank-detail-label">Account Name</span>
                                        <span class="bank-detail-value">Laundry Business Ltd</span>
                                    </div>
                                    <div class="bank-detail-row">
                                        <span class="bank-detail-label">Account Number</span>
                                        <span class="bank-detail-value">0123456789</span>
                                    </div>
                                    <div class="bank-detail-row">
                                        <span class="bank-detail-label">Bank</span>
                                        <span class="bank-detail-value">Sample Bank</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Transaction Reference">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-details">
                    <h3 class="payment-section-title">Order Summary</h3>
                    <div class="order-summary-details">
                        <div class="summary-detail-row">
                            <span class="summary-detail-label">Items</span>
                            <span id="itemsCount" class="summary-detail-value">0</span>
                        </div>
                        <div class="summary-detail-row">
                            <span class="summary-detail-label">Total Quantity</span>
                            <span id="totalQuantity" class="summary-detail-value">0</span>
                        </div>
                        <div class="summary-detail-row">
                            <span class="summary-detail-label">Subtotal</span>
                            <span id="paymentSubtotal" class="summary-detail-value">₦0.00</span>
                        </div>
                        <div id="paymentDiscountRow" class="summary-detail-row hidden">
                            <span class="summary-detail-label">Discount</span>
                            <span id="paymentDiscount" class="summary-detail-value discount-value">-₦0.00</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-detail-row total-row">
                            <span class="summary-detail-label">Total</span>
                            <span id="paymentTotal" class="summary-detail-value">₦0.00</span>
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="completePaymentBtn" class="btn btn-primary">Complete Payment</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Receipt</h2>
                <button class="close-btn" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="receipt-content">
                    <div class="receipt-header">
                        <h2 class="receipt-business-name">Laundry Business Ltd</h2>
                        <p class="receipt-business-address">123 Laundry Street, Lagos, Nigeria</p>
                        <p class="receipt-business-phone">Tel: 08012345678</p>
                    </div>

                    <div class="receipt-details">
                        <div class="receipt-detail-row">
                            <span class="receipt-detail-label">Receipt #:</span>
                            <span id="receiptNumber" class="receipt-detail-value">ORD-123456</span>
                        </div>
                        <div class="receipt-detail-row">
                            <span class="receipt-detail-label">Date:</span>
                            <span id="receiptDate" class="receipt-detail-value">01/01/2023 12:00 PM</span>
                        </div>
                        <div class="receipt-detail-row">
                            <span class="receipt-detail-label">Customer:</span>
                            <span id="receiptCustomer" class="receipt-detail-value">John Doe</span>
                        </div>
                        <div class="receipt-detail-row">
                            <span class="receipt-detail-label">Payment Method:</span>
                            <span id="receiptPaymentMethod" class="receipt-detail-value">Cash</span>
                        </div>
                    </div>

                    <div class="receipt-items">
                        <h3 class="receipt-section-title">Order Details</h3>
                        <table class="receipt-table">
                            <thead>
                                <tr>
                                    <th class="text-left">Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody id="receiptItems">
                                <!-- Receipt items will be populated here -->
                            </tbody>
                        </table>

                        <div class="receipt-summary">
                            <div class="receipt-summary-row">
                                <span class="receipt-summary-label">Subtotal</span>
                                <span id="receiptSubtotal" class="receipt-summary-value">₦0.00</span>
                            </div>
                            <div id="receiptDiscountRow" class="receipt-summary-row hidden">
                                <span class="receipt-summary-label">Discount</span>
                                <span id="receiptDiscount" class="receipt-summary-value">-₦0.00</span>
                            </div>
                            <div class="receipt-summary-row total-row">
                                <span class="receipt-summary-label">Total</span>
                                <span id="receiptTotal" class="receipt-summary-value">₦0.00</span>
                            </div>
                            <div id="receiptAmountPaidRow" class="receipt-summary-row hidden">
                                <span class="receipt-summary-label">Amount Paid</span>
                                <span id="receiptAmountPaid" class="receipt-summary-value">₦0.00</span>
                            </div>
                            <div id="receiptChangeRow" class="receipt-summary-row hidden">
                                <span class="receipt-summary-label">Change</span>
                                <span id="receiptChange" class="receipt-summary-value">₦0.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="receipt-footer">
                        <p>Thank you for your business!</p>
                        <p id="pickupDate">Items will be ready for pickup on 01/01/2023</p>
                    </div>
                </div>

                <div class="modal-actions">
                    <button id="printReceiptBtn" class="btn btn-secondary">
                        <i class="fas fa-print"></i> Print Receipt
                    </button>
                    <button id="newOrderBtn" class="btn btn-primary">New Order</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
<script src="{{ asset('js/pos.js') }}"></script>
@endsection

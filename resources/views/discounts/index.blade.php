<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('title', 'Discounts')

@section('page-title', 'Discounts')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/discounts.css') }}">
@endsection

@section('content')
        <!-- Filters and Actions -->
        <div class="filters-container">
            <div class="filters">
                <div class="filter-group">
                    <label for="statusFilter">Status:</label>
                    <select id="statusFilter" class="filter-select">
                        <option value="active">Active</option>
                        <option value="archived">Archived</option>
                        <option value="all">All</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="typeFilter">Type:</label>
                    <select id="typeFilter" class="filter-select">
                        <option value="">All Types</option>
                        <option value="percentage">Percentage</option>
                        <option value="amount">Fixed Amount</option>
                    </select>
                </div>
            </div>
            <div class="actions">
                <button id="addDiscountBtn" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Discount
                </button>
            </div>
        </div>

        <!-- Discounts Cards -->
        <div class="discount-cards" id="discountCards">
            <!-- Discount cards will be populated by JavaScript -->
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <p>Loading discounts...</p>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-container" id="paginationContainer">
            <div class="pagination-info">
                Showing <span id="paginationStart">0</span> to <span id="paginationEnd">0</span> of <span id="paginationTotal">0</span> discounts
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
@endsection

@section('modals')
    <!-- Add/Edit Discount Modal -->
    <div class="modal" id="discountModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Discount</h2>
                <button class="close-btn" id="closeModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="discountForm">
                    <input type="hidden" id="discountId">

                    <div class="form-group">
                        <label for="name">Discount Name</label>
                        <input type="text" id="name" name="name" placeholder="Summer Sale, New Customer, etc.">
                        <small>Leave blank for auto-generated name</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="type">Discount Type <span class="required">*</span></label>
                            <select id="type" name="type" required>
                                <option value="percentage">Percentage (%)</option>
                                <option value="amount">Fixed Amount (₦)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="value">Value <span class="required">*</span></label>
                            <input type="number" id="value" name="value" min="0" step="0.01" required>
                            <small id="valueHint">Enter percentage (e.g., 10 for 10%)</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" placeholder="Describe the discount and its conditions"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="expirationDate">Expiration Date</label>
                        <input type="date" id="expirationDate" name="expiration_date">
                        <small>Leave blank if the discount doesn't expire</small>
                    </div>

                    <div class="form-actions">
                        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                        <button type="submit" id="saveDiscountBtn" class="btn btn-primary">Save Discount</button>
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

@endsection

@section('scripts')
    <script src="{{ asset('js/services/discounts.js') }}"></script>
    <script src="{{ asset('js/pages/discounts.js') }}"></script>
@endsection

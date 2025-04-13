<!-- resources/views/Items/index.blade.php -->
@extends('layouts.app')

@section('title', 'Items')

@section('page-title', 'Items')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')
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
@endsection

@section('modals')
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
@endsection

@section('scripts')
    <script src="{{ asset('js/models/item.js') }}"></script>
    <script src="{{ asset('js/services/item.js') }}"></script>
    <script src="{{ asset('js/pages/items.js') }}"></script>
@endsection

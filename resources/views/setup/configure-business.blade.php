@extends('layouts.setup')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/verify-key.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="card wide-card">
            <div class="card-header">
                <div class="icon-container">
                    <i class="fas fa-building"></i>
                </div>
                <h1 class="card-title">Business Configuration</h1>
                <p class="card-description">Enter your business details to set up your application</p>
            </div>
            <div class="card-content">
                <form id="businessConfigForm">
                    {{-- <div class="form-row">
                        <div class="form-group">
                            <label for="businessName">Business Name</label>
                            <input type="text" id="businessName" name="business_name" placeholder="Acme Corporation" required>
                        </div>
                        <div class="form-group">
                            <label for="branch">Branch</label>
                            <input type="text" id="branch" name="branch_name" placeholder="Main">
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <label for="businessName">Business Name</label>
                        <input type="text" id="businessName" name="business_name" placeholder="Acme Corporation" required>
                    </div>
                    <div class="form-group">
                        <label for="branch">Branch</label>
                        <input type="text" id="branch" name="branch_name" placeholder="Main">
                    </div>

                    {{-- <div class="form-group">
                        <label for="address">Business Address</label>
                        <textarea id="address" name="address" placeholder="123 Business St, City, State, ZIP" rows="3" required></textarea>
                    </div> --}}

                    {{-- <div class="form-row">
                        <div class="form-group">
                            <label for="email">Business Email</label>
                            <input type="email" id="email" name="email" placeholder="contact@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="text" id="phoneNumber" name="phoneNumber" placeholder="+1 (555) 123-4567" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="logo">Business Logo</label>
                            <div class="file-upload">
                                <div class="file-upload-content">
                                    <i class="fas fa-upload"></i>
                                    <div class="file-upload-text">
                                        <span class="primary-text">Click to upload</span> or drag and drop
                                    </div>
                                    <p class="file-upload-description">PNG, JPG, GIF up to 2MB</p>
                                </div>
                                <input type="file" id="logo" name="logo" class="file-input">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="banner">Business Banner</label>
                            <div class="file-upload">
                                <div class="file-upload-content">
                                    <i class="fas fa-upload"></i>
                                    <div class="file-upload-text">
                                        <span class="primary-text">Click to upload</span> or drag and drop
                                    </div>
                                    <p class="file-upload-description">PNG, JPG, GIF up to 5MB</p>
                                </div>
                                <input type="file" id="banner" name="banner" class="file-input">
                            </div>
                        </div>
                    </div> --}}

                    <div class="card-footer">
                        <button type="submit" id="saveButton" class="btn btn-primary">
                            Continue to Admin Setup
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        window.appRoutes = {
            loginUrl: @json(route('login'))
        };
    </script>
    <script src="{{ asset('js/configure-business.js') }}"></script>
@endsection


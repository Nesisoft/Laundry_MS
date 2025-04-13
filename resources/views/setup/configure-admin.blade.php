@extends('layouts.setup')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/verify-key.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="icon-container">
                <i class="fas fa-user"></i>
            </div>
            <h1 class="card-title">Admin Account Setup</h1>
            <p class="card-description">Create your administrator account to manage the system</p>
        </div>
        <div class="card-content">
            <form id="adminSetupForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" placeholder="John" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" placeholder="Doe" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Username" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="••••••••" required>
                    </div>
                </div>

                <p id="errorMessage" class="error-message"></p>

                <div class="card-footer">
                    <button type="submit" id="createAccountButton" class="btn btn-primary">
                        Complete Setup
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        window.appRoutes = {
            configureBusinessUrl: @json(route('configureBusiness'))
        };
    </script>
    <script src="{{ asset('js/configure-admin.js') }}"></script>
@endsection

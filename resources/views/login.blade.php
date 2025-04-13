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
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <h1 class="card-title">Welcome Back</h1>
            <p class="card-description">Login to access your business management system</p>
        </div>
        <div class="card-content">
            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Username" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="label-with-link">
                        <label for="password">Password</label>
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="checkbox-container">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>

                <p id="errorMessage" class="error-message"></p>

                <div class="card-footer">
                    <button type="submit" id="loginButton" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>

    @endsection

    @section('scripts')
        <script>
            window.appRoutes = {
                dashboardUrl: @json(route('dashboard'))
            };
        </script>
        <script src="js/login.js"></script>
    @endsection

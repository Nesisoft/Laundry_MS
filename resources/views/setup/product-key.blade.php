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
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1 class="card-title">Product Verification</h1>
            <p class="card-description">Enter your product key to activate the application</p>
        </div>
        <div class="card-content">
            <form id="verifyKeyForm">
                <div class="form-group">
                    <input type="text" id="productKey" placeholder="XXXX-XXXX-XXXX-XXXX" class="text-center tracking-widest">
                    <p id="errorMessage" class="error-message"></p>
                </div>
                <div class="card-footer">
                    <button type="submit" id="verifyButton" class="btn btn-primary">
                        <i class="fas fa-check-circle"></i>
                        Verify Product Key
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        window.appRoutes = {
            configureAdminUrl: @json(route('configureAdmin'))
        };
    </script>

    <script src="{{ asset('js/verify-key.js') }}"></script>
@endsection

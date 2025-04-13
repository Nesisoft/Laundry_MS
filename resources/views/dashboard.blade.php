<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <h1 class="page-title">Dashboard</h1>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Total Revenue</h3>
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-value">$45,231.89</div>
                    <p class="stat-change">+20.1% from last month</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">New Customers</h3>
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-value">+2,350</div>
                    <p class="stat-change">+10.1% from last month</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Total Orders</h3>
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-value">+12,234</div>
                    <p class="stat-change">+35.1% from last month</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-header">
                    <h3 class="stat-card-title">Active Products</h3>
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-card-content">
                    <div class="stat-value">+573</div>
                    <p class="stat-change">+12 new products</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity and Sales Overview -->
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h2 class="dashboard-card-title">Recent Orders</h2>
                    <p class="dashboard-card-description">You have 265 orders this month</p>
                </div>
                <div class="dashboard-card-content">
                    <div class="recent-orders">
                        <!-- Order 1 -->
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-avatar">
                                    <span>C1</span>
                                </div>
                                <div class="order-details">
                                    <p class="order-id">Order #8721</p>
                                    <p class="order-customer">Customer 1</p>
                                </div>
                            </div>
                            <div class="order-value">
                                <p class="order-amount">$245.99</p>
                                <p class="order-date">04/10/2023</p>
                            </div>
                        </div>
                        <!-- Order 2 -->
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-avatar">
                                    <span>C2</span>
                                </div>
                                <div class="order-details">
                                    <p class="order-id">Order #9652</p>
                                    <p class="order-customer">Customer 2</p>
                                </div>
                            </div>
                            <div class="order-value">
                                <p class="order-amount">$789.45</p>
                                <p class="order-date">04/09/2023</p>
                            </div>
                        </div>
                        <!-- Order 3 -->
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-avatar">
                                    <span>C3</span>
                                </div>
                                <div class="order-details">
                                    <p class="order-id">Order #7123</p>
                                    <p class="order-customer">Customer 3</p>
                                </div>
                            </div>
                            <div class="order-value">
                                <p class="order-amount">$129.99</p>
                                <p class="order-date">04/08/2023</p>
                            </div>
                        </div>
                        <!-- Order 4 -->
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-avatar">
                                    <span>C4</span>
                                </div>
                                <div class="order-details">
                                    <p class="order-id">Order #5432</p>
                                    <p class="order-customer">Customer 4</p>
                                </div>
                            </div>
                            <div class="order-value">
                                <p class="order-amount">$349.50</p>
                                <p class="order-date">04/07/2023</p>
                            </div>
                        </div>
                        <!-- Order 5 -->
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-avatar">
                                    <span>C5</span>
                                </div>
                                <div class="order-details">
                                    <p class="order-id">Order #3298</p>
                                    <p class="order-customer">Customer 5</p>
                                </div>
                            </div>
                            <div class="order-value">
                                <p class="order-amount">$567.20</p>
                                <p class="order-date">04/06/2023</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h2 class="dashboard-card-title">Sales Overview</h2>
                    <p class="dashboard-card-description">Monthly revenue breakdown</p>
                </div>
                <div class="dashboard-card-content chart-container">
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-bar"></i>
                        <p>Sales chart visualization would appear here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="{{ asset('js/pages/dashboard.js') }}"></script>
@endsection

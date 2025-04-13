<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Laundry Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">

            <div class="sidebar-header">
                <h1 class="sidebar-title">Laundry App</h1>
                <button id="toggleSidebar" class="sidebar-toggle">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="sidebar-divider"></div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
                <a href="{{ route('services.index') }}" class="sidebar-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                    <i class="fas fa-concierge-bell"></i>
                    <span>Services</span>
                </a>
                <a href="{{ route('items.index') }}" class="sidebar-link {{ request()->routeIs('items.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Items</span>
                </a>
                <a href="{{ route('employees.index') }}" class="sidebar-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Employees</span>
                </a>
                <a href="{{ route('pickups.index') }}" class="sidebar-link {{ request()->routeIs('pickups.*') ? 'active' : '' }}">
                    <i class="fas fa-truck-pickup"></i>
                    <span>Pickups</span>
                </a>
                <a href="{{ route('deliveries.index') }}" class="sidebar-link {{ request()->routeIs('deliveries.*') ? 'active' : '' }}">
                    <i class="fas fa-truck"></i>
                    <span>Deliveries</span>
                </a>
                <a href="{{ route('invoices.index') }}" class="sidebar-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Invoices</span>
                </a>
                <a href="{{ route('discounts.index') }}" class="sidebar-link {{ request()->routeIs('discounts.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Discounts</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="/logout" class="sidebar-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-content">

                    <div class="page-header">
                        <h1>@yield('page-title')</h1>
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
                                    <span>{{ Auth::user()->initials ?? 'U' }}</span>
                                </div>
                                <div class="user-info">
                                    <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </button>

                            <div class="dropdown-menu" id="userDropdownMenu">
                                <div class="dropdown-header">My Account</div>
                                <div class="dropdown-divider"></div>
                                <a href="/profile-edit" class="dropdown-item">
                                    <i class="fas fa-user"></i>
                                    <span>Profile</span>
                                </a>
                                <a href="{{ route('settings.index') }}" class="dropdown-item">
                                    <i class="fas fa-cog"></i>
                                    <span>Settings</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="/logout" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Log out</span>
                                </a>
                            </div>

                        </div>

                    </div>

                </div>

            </header>

            <!-- Content -->
            <div class="content-wrapper">
                @yield('content')
            </div>

        </main>

    </div>

    <!-- Toast Notification -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Common Scripts -->
    <script src="{{ asset('js/services/api-service.js') }}"></script>
    <script src="{{ asset('js/ui/sidebar.js') }}"></script>
    <script src="{{ asset('js/ui/dropdown.js') }}"></script>
    <script src="{{ asset('js/ui/toast.js') }}"></script>

    @yield('scripts')

</body>
</html>

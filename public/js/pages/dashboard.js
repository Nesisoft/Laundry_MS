// public/js/pages/dashboard.js
document.addEventListener('DOMContentLoaded', function() {
    // You can add dashboard-specific JavaScript here
    // For example, fetching real-time data, initializing charts, etc.

    // Example: Initialize a chart (placeholder)
    initializeSalesChart();

    // Example: Load recent orders
    loadRecentOrders();

    // Example: Load dashboard stats
    loadDashboardStats();
});

function initializeSalesChart() {
    // This is a placeholder function
    // In a real application, you would initialize a chart library like Chart.js
    console.log('Sales chart initialized');

    // Example with Chart.js (commented out)
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Sales',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: '#3b82f6',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

async function loadRecentOrders() {
    // In a real application, you would fetch this data from your API
    // This is just a placeholder
    console.log('Loading recent orders');

    // Example API call (commented out)
    try {
        const response = await fetch('/api/orders/recent');
        const data = await response.json();

        if (data.success) {
            renderRecentOrders(data.orders);
        }
    } catch (error) {
        console.error('Error loading recent orders:', error);
    }
}

async function loadDashboardStats() {
    // In a real application, you would fetch this data from your API
    // This is just a placeholder
    console.log('Loading dashboard stats');

    // Example API call (commented out)
    try {
        const response = await fetch('/api/dashboard/stats');
        const data = await response.json();

        if (data.success) {
            updateDashboardStats(data.stats);
        }
    } catch (error) {
        console.error('Error loading dashboard stats:', error);
    }
}

function renderRecentOrders(orders) {
    // Example function to render orders from API data
    const ordersContainer = document.querySelector('.recent-orders');

    if (!orders || orders.length === 0) {
        ordersContainer.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="empty-state-title">No Recent Orders</h3>
                <p class="empty-state-description">
                    There are no recent orders to display.
                </p>
            </div>
        `;
        return;
    }

    ordersContainer.innerHTML = '';

    orders.forEach(order => {
        const orderItem = document.createElement('div');
        orderItem.className = 'order-item';

        // Get customer initials
        const initials = order.customer_name
            .split(' ')
            .map(name => name.charAt(0))
            .join('')
            .toUpperCase();

        // Format date
        const orderDate = new Date(order.created_at);
        const formattedDate = orderDate.toLocaleDateString('en-US', {
            month: '2-digit',
            day: '2-digit',
            year: 'numeric'
        });

        orderItem.innerHTML = `
            <div class="order-info">
                <div class="order-avatar">
                    <span>${initials}</span>
                </div>
                <div class="order-details">
                    <p class="order-id">Order #${order.id}</p>
                    <p class="order-customer">${order.customer_name}</p>
                </div>
            </div>
            <div class="order-value">
                <p class="order-amount">$${parseFloat(order.total).toFixed(2)}</p>
                <p class="order-date">${formattedDate}</p>
            </div>
        `;

        ordersContainer.appendChild(orderItem);
    });
}

function updateDashboardStats(stats) {
    // Example function to update dashboard stats from API data

    // Update revenue
    const revenueElement = document.querySelector('.stat-card:nth-child(1) .stat-value');
    const revenueChangeElement = document.querySelector('.stat-card:nth-child(1) .stat-change');

    if (revenueElement && stats.revenue) {
        revenueElement.textContent = `$${parseFloat(stats.revenue.value).toFixed(2)}`;
        revenueChangeElement.textContent = `${stats.revenue.change >= 0 ? '+' : ''}${stats.revenue.change}% from last month`;
        revenueChangeElement.style.color = stats.revenue.change >= 0 ? 'var(--green-800)' : 'var(--red-600)';
    }

    // Update customers
    const customersElement = document.querySelector('.stat-card:nth-child(2) .stat-value');
    const customersChangeElement = document.querySelector('.stat-card:nth-child(2) .stat-change');

    if (customersElement && stats.customers) {
        customersElement.textContent = `+${stats.customers.value}`;
        customersChangeElement.textContent = `${stats.customers.change >= 0 ? '+' : ''}${stats.customers.change}% from last month`;
        customersChangeElement.style.color = stats.customers.change >= 0 ? 'var(--green-800)' : 'var(--red-600)';
    }

    // Update orders
    const ordersElement = document.querySelector('.stat-card:nth-child(3) .stat-value');
    const ordersChangeElement = document.querySelector('.stat-card:nth-child(3) .stat-change');

    if (ordersElement && stats.orders) {
        ordersElement.textContent = `+${stats.orders.value}`;
        ordersChangeElement.textContent = `${stats.orders.change >= 0 ? '+' : ''}${stats.orders.change}% from last month`;
        ordersChangeElement.style.color = stats.orders.change >= 0 ? 'var(--green-800)' : 'var(--red-600)';
    }

    // Update products
    const productsElement = document.querySelector('.stat-card:nth-child(4) .stat-value');
    const productsChangeElement = document.querySelector('.stat-card:nth-child(4) .stat-change');

    if (productsElement && stats.products) {
        productsElement.textContent = `+${stats.products.value}`;
        productsChangeElement.textContent = `+${stats.products.new} new products`;
    }
}

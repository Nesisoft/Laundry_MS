<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Item;
use Carbon\Carbon;

class DashboardApiController extends Controller
{
    /**
     * Get dashboard statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        // Get current month and previous month
        $currentMonth = Carbon::now();
        $previousMonth = Carbon::now()->subMonth();

        // Calculate revenue
        $currentMonthRevenue = Order::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->sum('total');

        $previousMonthRevenue = Order::whereMonth('created_at', $previousMonth->month)
            ->whereYear('created_at', $previousMonth->year)
            ->sum('total');

        $revenueChange = $previousMonthRevenue > 0
            ? round((($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 1)
            : 100;

        // Calculate customers
        $currentMonthCustomers = Customer::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();

        $previousMonthCustomers = Customer::whereMonth('created_at', $previousMonth->month)
            ->whereYear('created_at', $previousMonth->year)
            ->count();

        $customersChange = $previousMonthCustomers > 0
            ? round((($currentMonthCustomers - $previousMonthCustomers) / $previousMonthCustomers) * 100, 1)
            : 100;

        // Calculate orders
        $currentMonthOrders = Order::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();

        $previousMonthOrders = Order::whereMonth('created_at', $previousMonth->month)
            ->whereYear('created_at', $previousMonth->year)
            ->count();

        $ordersChange = $previousMonthOrders > 0
            ? round((($currentMonthOrders - $previousMonthOrders) / $previousMonthOrders) * 100, 1)
            : 100;

        // Calculate products
        $totalProducts = Item::where('status', 'active')->count();
        $newProducts = Item::where('status', 'active')
            ->whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();

        return response()->json([
            'success' => true,
            'stats' => [
                'revenue' => [
                    'value' => $currentMonthRevenue,
                    'change' => $revenueChange
                ],
                'customers' => [
                    'value' => $currentMonthCustomers,
                    'change' => $customersChange
                ],
                'orders' => [
                    'value' => $currentMonthOrders,
                    'change' => $ordersChange
                ],
                'products' => [
                    'value' => $totalProducts,
                    'new' => $newProducts
                ]
            ]
        ]);
    }
}

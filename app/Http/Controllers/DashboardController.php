<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function pos()
    {
        return view('pos');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PickupController extends Controller
{
    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pickups.index');
    }
}

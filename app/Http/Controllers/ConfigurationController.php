<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function verifyProductKey()
    {
        return view('setup.product-key');
    }

    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function configureBusiness()
    {
        return view('setup.configure-business');
    }

    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function configureAdmin()
    {
        return view('setup.configure-admin');
    }

    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function login()
    {
        return view('login');
    }
}

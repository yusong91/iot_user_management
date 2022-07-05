<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Vanguard\Http\Controllers\Controller;

class DashboardController extends Controller
{
    
    public function index()
    {
        if (session()->has('verified')) {
            session()->flash('success', __('E-Mail verified successfully.'));
        }
        
        return view('dashboard.index');
    }
}

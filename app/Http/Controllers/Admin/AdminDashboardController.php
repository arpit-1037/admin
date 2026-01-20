<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    //
    // public function index(Request $request)
    // {
        // Temporary response until Blade is added
        // return response('Admin Dashboard', 200);
    //     return redirect('admin.dashboard');
    // }
    public function index(Request $request)
    {
        // return redirect()->route('admin.dashboard');
        return view('admin.dashboard');
    }
}

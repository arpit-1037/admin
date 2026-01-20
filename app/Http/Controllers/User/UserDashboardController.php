<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    //
    public function index(Request $request)
    {
        // Temporary response until Blade is added
        return response('User Dashboard', 200);
    }
}

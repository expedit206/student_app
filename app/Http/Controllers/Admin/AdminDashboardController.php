<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class AdminDashboardController extends Controller
{
     function dashboard()
    {
        return inertia('Admin/AdminDashboard');
    }
}

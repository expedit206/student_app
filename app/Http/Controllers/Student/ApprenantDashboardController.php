<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApprenantDashboardController extends Controller
{
    function dashboard()
    {
        return inertia('Apprenants/Dashboard');
    }}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Redirect to specific dashboard based on role
        return view("dashboard.{$user->role}", compact('user'));
    }
}

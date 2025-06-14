<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{    public function index()
    {
        $user = Auth::user();
        
        // Get user's first role
        $role = $user->roles()->first();
        $roleName = $role ? $role->name : 'default';
        
        // Try to load role-specific dashboard, fallback to default if not found
        if (view()->exists("dashboard.{$roleName}")) {
            return view("dashboard.{$roleName}", compact('user'));
        }
        
        return view('dashboard', compact('user'));
    }
}

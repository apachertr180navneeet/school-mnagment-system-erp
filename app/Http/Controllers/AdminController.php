<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{

    /**
     * 🔹 Logout Admin User
     */
    public function Logout(){
        try {

            // Logout authenticated user
            Auth::logout();

            // Redirect to login page
            return redirect()->route('login')->with([
                'message' => 'Logged out successfully!',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            // Log error for debugging
            Log::error('Logout Error: '.$e->getMessage());

            // Redirect back with error message
            return back()->with([
                'message' => 'Logout failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
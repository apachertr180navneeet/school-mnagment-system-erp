<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{

    public function dashboard()
    {
        return view('admin.index', [

            'totalStudents' => '30',
            'totalTeachers' => '40',
            'totalFees' => '50',
            'attendance' => '92%',

            // ✅ Static recent students
            'recentStudents' => [
                (object)[
                    'name' => 'Rahul',
                    'class' => '10th',
                    'status' => 'Active'
                ],
                (object)[
                    'name' => 'Pooja',
                    'class' => '9th',
                    'status' => 'Pending'
                ],
                (object)[
                    'name' => 'Amit',
                    'class' => '8th',
                    'status' => 'Active'
                ],
            ],

            // ✅ Chart static data
            'months' => ['Jan','Feb','Mar','Apr'],
            'studentCounts' => [200, 400, 600, 900]
        ]);
    }

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
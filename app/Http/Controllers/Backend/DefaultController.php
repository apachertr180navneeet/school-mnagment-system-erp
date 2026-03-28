<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignStudent;
use App\Models\AssignSubject;
use Illuminate\Support\Facades\Log;

class DefaultController extends Controller
{

    /**
     * 🔹 Get subjects based on class ID (AJAX)
     */
    public function GetSubject(Request $request){
        try {

            // Get class ID from request
            $class_id = $request->class_id;

            // Fetch subjects with relationship
            $allData = AssignSubject::with(['school_subject'])
                        ->where('class_id', $class_id)
                        ->get();

            // Return JSON response
            return response()->json([
                'status' => 'success',
                'data' => $allData
            ]);

        } catch (\Exception $e) {

            // Log error
            Log::error('GetSubject Error: '.$e->getMessage());

            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load subjects'
            ], 500);
        }
    }


    /**
     * 🔹 Get students based on year & class (AJAX)
     */
    public function GetStudents(Request $request){
        try {

            // Get request data
            $year_id  = $request->year_id;
            $class_id = $request->class_id;

            // Fetch students with relationship
            $allData = AssignStudent::with(['student'])
                        ->where('year_id', $year_id)
                        ->where('class_id', $class_id)
                        ->get();

            // Return JSON response
            return response()->json([
                'status' => 'success',
                'data' => $allData
            ]);

        } catch (\Exception $e) {

            // Log error
            Log::error('GetStudents Error: '.$e->getMessage());

            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load students'
            ], 500);
        }
    }

}
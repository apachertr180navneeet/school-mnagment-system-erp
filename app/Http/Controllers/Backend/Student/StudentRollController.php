<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignStudent;
use App\Models\StudentYear;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Log;

class StudentRollController extends Controller
{

    /**
     * 🔹 Show roll generate page
     */
    public function StudentRollView(){
        try {

            $data['years'] = StudentYear::all();
            $data['classes'] = StudentClass::all();

            return view('backend.student.roll_generate.roll_generate_view', $data);

        } catch (\Exception $e) {

            Log::error('StudentRollView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Get students based on year & class (AJAX)
     */
    public function GetStudents(Request $request){
        try {

            $allData = AssignStudent::with(['student'])
                        ->where('year_id', $request->year_id)
                        ->where('class_id', $request->class_id)
                        ->get();

            return response()->json([
                'status' => 'success',
                'data' => $allData
            ]);

        } catch (\Exception $e) {

            Log::error('GetStudents Error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch students'
            ], 500);
        }
    }


    /**
     * 🔹 Store / update student roll numbers
     */
    public function StudentRollStore(Request $request){
        try {

            $year_id = $request->year_id;
            $class_id = $request->class_id;

            // Check if students exist
            if (!empty($request->student_id)) {

                foreach ($request->student_id as $index => $student_id) {

                    AssignStudent::where('year_id', $year_id)
                        ->where('class_id', $class_id)
                        ->where('student_id', $student_id)
                        ->update([
                            'roll' => $request->roll[$index]
                        ]);
                }

                return redirect()->route('roll.generate.view')->with([
                    'message' => 'Roll Generated Successfully',
                    'alert-type' => 'success'
                ]);

            } else {

                return back()->with([
                    'message' => 'No students found!',
                    'alert-type' => 'error'
                ]);
            }

        } catch (\Exception $e) {

            Log::error('StudentRollStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Roll generation failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
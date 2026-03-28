<?php

namespace App\Http\Controllers\Backend\Marks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentYear;
use App\Models\StudentClass;
use App\Models\ExamType;
use App\Models\StudentMarks;
use Illuminate\Support\Facades\Log;

class MarksController extends Controller
{

    /**
     * 🔹 Show marks add page
     */
    public function MarksAdd(){
        try {

            $data['years'] = StudentYear::all();
            $data['classes'] = StudentClass::all();
            $data['exam_types'] = ExamType::all();

            return view('backend.marks.marks_add', $data);

        } catch (\Exception $e) {

            Log::error('MarksAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store student marks
     */
    public function MarksStore(Request $request){
        try {

            if (!empty($request->student_id)) {

                foreach ($request->student_id as $index => $student_id) {

                    $data = new StudentMarks();
                    $data->year_id = $request->year_id;
                    $data->class_id = $request->class_id;
                    $data->assign_subject_id = $request->assign_subject_id;
                    $data->exam_type_id = $request->exam_type_id;
                    $data->student_id = $student_id;
                    $data->id_no = $request->id_no[$index];
                    $data->marks = $request->marks[$index];
                    $data->save();
                }
            }

            return redirect()->back()->with([
                'message' => 'Student Marks Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('MarksStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Marks insert failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show marks edit page
     */
    public function MarksEdit(){
        try {

            $data['years'] = StudentYear::all();
            $data['classes'] = StudentClass::all();
            $data['exam_types'] = ExamType::all();

            return view('backend.marks.marks_edit', $data);

        } catch (\Exception $e) {

            Log::error('MarksEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Get students marks (AJAX)
     */
    public function MarksEditGetStudents(Request $request){
        try {

            $getStudent = StudentMarks::with(['student'])
                ->where('year_id', $request->year_id)
                ->where('class_id', $request->class_id)
                ->where('assign_subject_id', $request->assign_subject_id)
                ->where('exam_type_id', $request->exam_type_id)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $getStudent
            ]);

        } catch (\Exception $e) {

            Log::error('MarksEditGetStudents Error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch marks'
            ], 500);
        }
    }


    /**
     * 🔹 Update student marks
     */
    public function MarksUpdate(Request $request){
        try {

            // Delete old records
            StudentMarks::where('year_id', $request->year_id)
                ->where('class_id', $request->class_id)
                ->where('assign_subject_id', $request->assign_subject_id)
                ->where('exam_type_id', $request->exam_type_id)
                ->delete();

            // Insert new records
            if (!empty($request->student_id)) {

                foreach ($request->student_id as $index => $student_id) {

                    $data = new StudentMarks();
                    $data->year_id = $request->year_id;
                    $data->class_id = $request->class_id;
                    $data->assign_subject_id = $request->assign_subject_id;
                    $data->exam_type_id = $request->exam_type_id;
                    $data->student_id = $student_id;
                    $data->id_no = $request->id_no[$index];
                    $data->marks = $request->marks[$index];
                    $data->save();
                }
            }

            return redirect()->back()->with([
                'message' => 'Student Marks Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('MarksUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Marks update failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
<?php

namespace App\Http\Controllers\Backend\Marks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MarksGrade;
use Illuminate\Support\Facades\Log;

class GradeController extends Controller
{

    /**
     * 🔹 View all grade marks
     */
    public function MarksGradeView(){
        try {

            $data['allData'] = MarksGrade::all();

            return view('backend.marks.grade_marks_view', $data);

        } catch (\Exception $e) {

            Log::error('MarksGradeView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load grade data!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add grade form
     */
    public function MarksGradeAdd(){
        try {

            return view('backend.marks.grade_marks_add');

        } catch (\Exception $e) {

            Log::error('MarksGradeAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store new grade
     */
    public function MarksGradeStore(Request $request){
        try {

            $data = new MarksGrade();
            $data->grade_name = $request->grade_name;
            $data->grade_point = $request->grade_point;
            $data->start_marks = $request->start_marks;
            $data->end_marks = $request->end_marks;
            $data->start_point = $request->start_point;
            $data->end_point = $request->end_point;
            $data->remarks = $request->remarks;
            $data->save();

            return redirect()->route('marks.entry.grade')->with([
                'message' => 'Grade Marks Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('MarksGradeStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Grade creation failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Edit grade
     */
    public function MarksGradeEdit($id){
        try {

            $data['editData'] = MarksGrade::findOrFail($id);

            return view('backend.marks.grade_marks_edit', $data);

        } catch (\Exception $e) {

            Log::error('MarksGradeEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Grade not found!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update grade
     */
    public function MarksGradeUpdate(Request $request, $id){
        try {

            $data = MarksGrade::findOrFail($id);

            $data->grade_name = $request->grade_name;
            $data->grade_point = $request->grade_point;
            $data->start_marks = $request->start_marks;
            $data->end_marks = $request->end_marks;
            $data->start_point = $request->start_point;
            $data->end_point = $request->end_point;
            $data->remarks = $request->remarks;
            $data->save();

            return redirect()->route('marks.entry.grade')->with([
                'message' => 'Grade Marks Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('MarksGradeUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Grade update failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
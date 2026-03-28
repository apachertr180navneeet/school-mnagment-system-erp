<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamType;
use Illuminate\Support\Facades\Log;

class ExamTypeController extends Controller
{

    /**
     * 🔹 View all exam types
     */
    public function ViewExamType(){
        try {

            $data['allData'] = ExamType::all();

            return view('backend.setup.exam_type.view_exam_type', $data);

        } catch (\Exception $e) {

            Log::error('ViewExamType Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load exam types!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add exam type page
     */
    public function ExamTypeAdd(){
        try {

            return view('backend.setup.exam_type.add_exam_type');

        } catch (\Exception $e) {

            Log::error('ExamTypeAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store exam type
     */
    public function ExamTypeStore(Request $request){
        try {

            // Validation
            $request->validate([
                'name' => 'required|unique:exam_types,name',
            ]);

            // Save
            $data = new ExamType();
            $data->name = $request->name;
            $data->save();

            return redirect()->route('exam.type.view')->with([
                'message' => 'Exam Type Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('ExamTypeStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Insert failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Edit exam type
     */
    public function ExamTypeEdit($id){
        try {

            $editData = ExamType::findOrFail($id);

            return view('backend.setup.exam_type.edit_exam_type', compact('editData'));

        } catch (\Exception $e) {

            Log::error('ExamTypeEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Exam type not found!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update exam type
     */
    public function ExamTypeUpdate(Request $request, $id){
        try {

            $data = ExamType::findOrFail($id);

            // Validation
            $request->validate([
                'name' => 'required|unique:exam_types,name,'.$data->id
            ]);

            // Update
            $data->name = $request->name;
            $data->save();

            return redirect()->route('exam.type.view')->with([
                'message' => 'Exam Type Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('ExamTypeUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Delete exam type
     */
    public function ExamTypeDelete($id){
        try {

            $examType = ExamType::findOrFail($id);
            $examType->delete();

            return redirect()->route('exam.type.view')->with([
                'message' => 'Exam Type Deleted Successfully',
                'alert-type' => 'info'
            ]);

        } catch (\Exception $e) {

            Log::error('ExamTypeDelete Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Delete failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
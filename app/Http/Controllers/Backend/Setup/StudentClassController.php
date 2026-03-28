<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Log;

class StudentClassController extends Controller
{

    /**
     * 🔹 View all student classes
     */
    public function ViewStudent(){
        try {

            $data['allData'] = StudentClass::all();

            return view('backend.setup.student_class.view_class', $data);

        } catch (\Exception $e) {

            Log::error('ViewStudent Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load classes!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add class page
     */
    public function StudentClassAdd(){
        try {

            return view('backend.setup.student_class.add_class');

        } catch (\Exception $e) {

            Log::error('StudentClassAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store student class
     */
    public function StudentClassStore(Request $request){
        try {

            // Validation
            $request->validate([
                'name' => 'required|unique:student_classes,name',
            ]);

            // Save
            $data = new StudentClass();
            $data->name = $request->name;
            $data->save();

            return redirect()->route('student.class.view')->with([
                'message' => 'Student Class Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('StudentClassStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Insert failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Edit student class
     */
    public function StudentClassEdit($id){
        try {

            $editData = StudentClass::findOrFail($id);

            return view('backend.setup.student_class.edit_class', compact('editData'));

        } catch (\Exception $e) {

            Log::error('StudentClassEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Class not found!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update student class
     */
    public function StudentClassUpdate(Request $request, $id){
        try {

            $data = StudentClass::findOrFail($id);

            // Validation
            $request->validate([
                'name' => 'required|unique:student_classes,name,'.$data->id
            ]);

            // Update
            $data->name = $request->name;
            $data->save();

            return redirect()->route('student.class.view')->with([
                'message' => 'Student Class Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('StudentClassUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Delete student class
     */
    public function StudentClassDelete($id){
        try {

            $studentClass = StudentClass::findOrFail($id);
            $studentClass->delete();

            return redirect()->route('student.class.view')->with([
                'message' => 'Student Class Deleted Successfully',
                'alert-type' => 'info'
            ]);

        } catch (\Exception $e) {

            Log::error('StudentClassDelete Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Delete failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
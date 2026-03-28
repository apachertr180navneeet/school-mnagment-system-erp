<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentShift;
use Illuminate\Support\Facades\Log;

class StudentShiftController extends Controller
{

    /**
     * 🔹 View all student shifts
     */
    public function ViewShift(){
        try {

            $data['allData'] = StudentShift::all();

            return view('backend.setup.shift.view_shift', $data);

        } catch (\Exception $e) {

            Log::error('ViewShift Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load shifts!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add shift page
     */
    public function StudentShiftAdd(){
        try {

            return view('backend.setup.shift.add_shift');

        } catch (\Exception $e) {

            Log::error('StudentShiftAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store student shift
     */
    public function StudentShiftStore(Request $request){
        try {

            // Validation
            $request->validate([
                'name' => 'required|unique:student_shifts,name',
            ]);

            // Save
            $data = new StudentShift();
            $data->name = $request->name;
            $data->save();

            return redirect()->route('student.shift.view')->with([
                'message' => 'Student Shift Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('StudentShiftStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Insert failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Edit student shift
     */
    public function StudentShiftEdit($id){
        try {

            $editData = StudentShift::findOrFail($id);

            return view('backend.setup.shift.edit_shift', compact('editData'));

        } catch (\Exception $e) {

            Log::error('StudentShiftEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Shift not found!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update student shift
     */
    public function StudentShiftUpdate(Request $request, $id){
        try {

            $data = StudentShift::findOrFail($id);

            // Validation
            $request->validate([
                'name' => 'required|unique:student_shifts,name,'.$data->id
            ]);

            // Update
            $data->name = $request->name;
            $data->save();

            return redirect()->route('student.shift.view')->with([
                'message' => 'Student Shift Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('StudentShiftUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Delete student shift
     */
    public function StudentShiftDelete($id){
        try {

            $studentShift = StudentShift::findOrFail($id);
            $studentShift->delete();

            return redirect()->route('student.shift.view')->with([
                'message' => 'Student Shift Deleted Successfully',
                'alert-type' => 'info'
            ]);

        } catch (\Exception $e) {

            Log::error('StudentShiftDelete Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Delete failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
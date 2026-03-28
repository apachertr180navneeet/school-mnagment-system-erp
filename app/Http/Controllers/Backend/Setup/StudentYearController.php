<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentYear;
use Illuminate\Support\Facades\Log;

class StudentYearController extends Controller
{

    /**
     * 🔹 View all student years
     */
    public function ViewYear(){
        try {

            $data['allData'] = StudentYear::all();

            return view('backend.setup.year.view_year', $data);

        } catch (\Exception $e) {

            Log::error('ViewYear Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load years!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add year page
     */
    public function StudentYearAdd(){
        try {

            return view('backend.setup.year.add_year');

        } catch (\Exception $e) {

            Log::error('StudentYearAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store student year
     */
    public function StudentYearStore(Request $request){
        try {

            // Validation
            $request->validate([
                'name' => 'required|unique:student_years,name',
            ]);

            // Save
            $data = new StudentYear();
            $data->name = $request->name;
            $data->save();

            return redirect()->route('student.year.view')->with([
                'message' => 'Student Year Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('StudentYearStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Insert failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Edit student year
     */
    public function StudentYearEdit($id){
        try {

            $editData = StudentYear::findOrFail($id);

            return view('backend.setup.year.edit_year', compact('editData'));

        } catch (\Exception $e) {

            Log::error('StudentYearEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Year not found!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update student year
     */
    public function StudentYearUpdate(Request $request, $id){
        try {

            $data = StudentYear::findOrFail($id);

            // Validation
            $request->validate([
                'name' => 'required|unique:student_years,name,'.$data->id
            ]);

            // Update
            $data->name = $request->name;
            $data->save();

            return redirect()->route('student.year.view')->with([
                'message' => 'Student Year Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('StudentYearUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Delete student year
     */
    public function StudentYearDelete($id){
        try {

            $studentYear = StudentYear::findOrFail($id);
            $studentYear->delete();

            return redirect()->route('student.year.view')->with([
                'message' => 'Student Year Deleted Successfully',
                'alert-type' => 'info'
            ]);

        } catch (\Exception $e) {

            Log::error('StudentYearDelete Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Delete failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
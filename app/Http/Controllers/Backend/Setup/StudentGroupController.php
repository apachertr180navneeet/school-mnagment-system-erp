<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentGroup;
use Illuminate\Support\Facades\Log;

class StudentGroupController extends Controller
{

    /**
     * 🔹 View all student groups
     */
    public function ViewGroup(){
        try {

            $data['allData'] = StudentGroup::all();

            return view('backend.setup.group.view_group', $data);

        } catch (\Exception $e) {

            Log::error('ViewGroup Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load groups!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add group page
     */
    public function StudentGroupAdd(){
        try {

            return view('backend.setup.group.add_group');

        } catch (\Exception $e) {

            Log::error('StudentGroupAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store student group
     */
    public function StudentGroupStore(Request $request){
        try {

            // Validation
            $request->validate([
                'name' => 'required|unique:student_groups,name',
            ]);

            // Save
            $data = new StudentGroup();
            $data->name = $request->name;
            $data->save();

            return redirect()->route('student.group.view')->with([
                'message' => 'Student Group Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('StudentGroupStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Insert failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Edit student group
     */
    public function StudentGroupEdit($id){
        try {

            $editData = StudentGroup::findOrFail($id);

            return view('backend.setup.group.edit_group', compact('editData'));

        } catch (\Exception $e) {

            Log::error('StudentGroupEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Group not found!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update student group
     */
    public function StudentGroupUpdate(Request $request, $id){
        try {

            $data = StudentGroup::findOrFail($id);

            // Validation
            $request->validate([
                'name' => 'required|unique:student_groups,name,'.$data->id
            ]);

            // Update
            $data->name = $request->name;
            $data->save();

            return redirect()->route('student.group.view')->with([
                'message' => 'Student Group Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('StudentGroupUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Delete student group
     */
    public function StudentGroupDelete($id){
        try {

            $studentGroup = StudentGroup::findOrFail($id);
            $studentGroup->delete();

            return redirect()->route('student.group.view')->with([
                'message' => 'Student Group Deleted Successfully',
                'alert-type' => 'info'
            ]);

        } catch (\Exception $e) {

            Log::error('StudentGroupDelete Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Delete failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
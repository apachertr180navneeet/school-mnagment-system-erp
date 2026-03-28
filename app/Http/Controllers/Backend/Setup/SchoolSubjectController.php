<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolSubject;
use Illuminate\Support\Facades\Log;

class SchoolSubjectController extends Controller
{

    /**
     * 🔹 View all subjects
     */
    public function ViewSubject(){
        try {

            $data['allData'] = SchoolSubject::all();

            return view('backend.setup.school_subject.view_school_subject', $data);

        } catch (\Exception $e) {

            Log::error('ViewSubject Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load subjects!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add subject page
     */
    public function SubjectAdd(){
        try {

            return view('backend.setup.school_subject.add_school_subject');

        } catch (\Exception $e) {

            Log::error('SubjectAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store subject
     */
    public function SubjectStore(Request $request){
        try {

            // Validation
            $request->validate([
                'name' => 'required|unique:school_subjects,name',
            ]);

            // Save
            $data = new SchoolSubject();
            $data->name = $request->name;
            $data->save();

            return redirect()->route('school.subject.view')->with([
                'message' => 'Subject Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('SubjectStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Insert failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Edit subject
     */
    public function SubjectEdit($id){
        try {

            $editData = SchoolSubject::findOrFail($id);

            return view('backend.setup.school_subject.edit_school_subject', compact('editData'));

        } catch (\Exception $e) {

            Log::error('SubjectEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Subject not found!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update subject
     */
    public function SubjectUpdate(Request $request, $id){
        try {

            $data = SchoolSubject::findOrFail($id);

            // Validation
            $request->validate([
                'name' => 'required|unique:school_subjects,name,'.$data->id
            ]);

            // Update
            $data->name = $request->name;
            $data->save();

            return redirect()->route('school.subject.view')->with([
                'message' => 'Subject Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('SubjectUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Delete subject
     */
    public function SubjectDelete($id){
        try {

            $subject = SchoolSubject::findOrFail($id);
            $subject->delete();

            return redirect()->route('school.subject.view')->with([
                'message' => 'Subject Deleted Successfully',
                'alert-type' => 'info'
            ]);

        } catch (\Exception $e) {

            Log::error('SubjectDelete Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Delete failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolSubject;
use App\Models\StudentClass;
use App\Models\AssignSubject;
use Illuminate\Support\Facades\Log;

class AssignSubjectController extends Controller
{

    /**
     * 🔹 View assigned subjects (grouped by class)
     */
    public function ViewAssignSubject(){
        try {

            $data['allData'] = AssignSubject::select('class_id')
                                ->groupBy('class_id')
                                ->get();

            return view('backend.setup.assign_subject.view_assign_subject', $data);

        } catch (\Exception $e) {

            Log::error('ViewAssignSubject Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load data!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add assign subject page
     */
    public function AddAssignSubject(){
        try {

            $data['subjects'] = SchoolSubject::all();
            $data['classes']  = StudentClass::all();

            return view('backend.setup.assign_subject.add_assign_subject', $data);

        } catch (\Exception $e) {

            Log::error('AddAssignSubject Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store assigned subjects
     */
    public function StoreAssignSubject(Request $request){
        try {

            if (!empty($request->subject_id)) {

                foreach ($request->subject_id as $index => $subject_id) {

                    $assign_subject = new AssignSubject();
                    $assign_subject->class_id = $request->class_id;
                    $assign_subject->subject_id = $subject_id;
                    $assign_subject->full_mark = $request->full_mark[$index];
                    $assign_subject->pass_mark = $request->pass_mark[$index];
                    $assign_subject->subjective_mark = $request->subjective_mark[$index];
                    $assign_subject->save();
                }
            }

            return redirect()->route('assign.subject.view')->with([
                'message' => 'Subjects Assigned Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('StoreAssignSubject Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Assignment failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Edit assigned subjects
     */
    public function EditAssignSubject($class_id){
        try {

            $data['editData'] = AssignSubject::where('class_id', $class_id)
                                ->orderBy('subject_id', 'asc')
                                ->get();

            $data['subjects'] = SchoolSubject::all();
            $data['classes']  = StudentClass::all();

            return view('backend.setup.assign_subject.edit_assign_subject', $data);

        } catch (\Exception $e) {

            Log::error('EditAssignSubject Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load data!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update assigned subjects
     */
    public function UpdateAssignSubject(Request $request, $class_id){
        try {

            if (empty($request->subject_id)) {

                return redirect()->route('assign.subject.edit', $class_id)->with([
                    'message' => 'Please select at least one subject!',
                    'alert-type' => 'error'
                ]);
            }

            // Delete old subjects
            AssignSubject::where('class_id', $class_id)->delete();

            // Insert new subjects
            foreach ($request->subject_id as $index => $subject_id) {

                $assign_subject = new AssignSubject();
                $assign_subject->class_id = $request->class_id;
                $assign_subject->subject_id = $subject_id;
                $assign_subject->full_mark = $request->full_mark[$index];
                $assign_subject->pass_mark = $request->pass_mark[$index];
                $assign_subject->subjective_mark = $request->subjective_mark[$index];
                $assign_subject->save();
            }

            return redirect()->route('assign.subject.view')->with([
                'message' => 'Subjects Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('UpdateAssignSubject Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show assigned subject details
     */
    public function DetailsAssignSubject($class_id){
        try {

            $data['detailsData'] = AssignSubject::where('class_id', $class_id)
                                    ->orderBy('subject_id', 'asc')
                                    ->get();

            return view('backend.setup.assign_subject.details_assign_subject', $data);

        } catch (\Exception $e) {

            Log::error('DetailsAssignSubject Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load details!',
                'alert-type' => 'error'
            ]);
        }
    }

}
<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Designation;
use Illuminate\Support\Facades\Log;

class DesignationController extends Controller
{

    /**
     * 🔹 View all designations
     */
    public function ViewDesignation(){
        try {

            $data['allData'] = Designation::all();

            return view('backend.setup.designation.view_designation', $data);

        } catch (\Exception $e) {

            Log::error('ViewDesignation Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load designations!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add designation page
     */
    public function DesignationAdd(){
        try {

            return view('backend.setup.designation.add_designation');

        } catch (\Exception $e) {

            Log::error('DesignationAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store new designation
     */
    public function DesignationStore(Request $request){
        try {

            // Validation
            $request->validate([
                'name' => 'required|unique:designations,name',
            ]);

            // Save
            $data = new Designation();
            $data->name = $request->name;
            $data->save();

            return redirect()->route('designation.view')->with([
                'message' => 'Designation Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('DesignationStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to insert designation!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Edit designation
     */
    public function DesignationEdit($id){
        try {

            $editData = Designation::findOrFail($id);

            return view('backend.setup.designation.edit_designation', compact('editData'));

        } catch (\Exception $e) {

            Log::error('DesignationEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Designation not found!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update designation
     */
    public function DesignationUpdate(Request $request, $id){
        try {

            $data = Designation::findOrFail($id);

            // Validation
            $request->validate([
                'name' => 'required|unique:designations,name,'.$data->id
            ]);

            // Update
            $data->name = $request->name;
            $data->save();

            return redirect()->route('designation.view')->with([
                'message' => 'Designation Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('DesignationUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Delete designation
     */
    public function DesignationDelete($id){
        try {

            $designation = Designation::findOrFail($id);
            $designation->delete();

            return redirect()->route('designation.view')->with([
                'message' => 'Designation Deleted Successfully',
                'alert-type' => 'info'
            ]);

        } catch (\Exception $e) {

            Log::error('DesignationDelete Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Delete failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
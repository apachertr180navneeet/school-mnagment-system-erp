<?php

namespace App\Http\Controllers\Backend\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmployeeLeave;
use App\Models\LeavePurpose;
use Illuminate\Support\Facades\Log;

class EmployeeLeaveController extends Controller
{

    /**
     * 🔹 View all employee leave data
     */
    public function LeaveView(){
        try {

            $data['allData'] = EmployeeLeave::orderBy('id', 'desc')->get();

            return view('backend.employee.employee_leave.employee_leave_view', $data);

        } catch (\Exception $e) {

            Log::error('LeaveView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load leave data!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add leave form
     */
    public function LeaveAdd(){
        try {

            $data['employees'] = User::where('usertype', 'employee')->get();
            $data['leave_purpose'] = LeavePurpose::all();

            return view('backend.employee.employee_leave.employee_leave_add', $data);

        } catch (\Exception $e) {

            Log::error('LeaveAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store employee leave
     */
    public function LeaveStore(Request $request){
        try {

            // Handle new leave purpose
            if ($request->leave_purpose_id == "0") {
                $leavepurpose = new LeavePurpose();
                $leavepurpose->name = $request->name;
                $leavepurpose->save();
                $leave_purpose_id = $leavepurpose->id;
            } else {
                $leave_purpose_id = $request->leave_purpose_id;
            }

            // Save leave data
            $data = new EmployeeLeave();
            $data->employee_id = $request->employee_id;
            $data->leave_purpose_id = $leave_purpose_id;
            $data->start_date = date('Y-m-d', strtotime($request->start_date));
            $data->end_date = date('Y-m-d', strtotime($request->end_date));
            $data->save();

            return redirect()->route('employee.leave.view')->with([
                'message' => 'Employee Leave Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('LeaveStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Leave creation failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Edit leave
     */
    public function LeaveEdit($id){
        try {

            $data['editData'] = EmployeeLeave::findOrFail($id);
            $data['employees'] = User::where('usertype', 'employee')->get();
            $data['leave_purpose'] = LeavePurpose::all();

            return view('backend.employee.employee_leave.employee_leave_edit', $data);

        } catch (\Exception $e) {

            Log::error('LeaveEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Leave not found!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update leave
     */
    public function LeaveUpdate(Request $request, $id){
        try {

            // Handle new leave purpose
            if ($request->leave_purpose_id == "0") {
                $leavepurpose = new LeavePurpose();
                $leavepurpose->name = $request->name;
                $leavepurpose->save();
                $leave_purpose_id = $leavepurpose->id;
            } else {
                $leave_purpose_id = $request->leave_purpose_id;
            }

            // Update leave
            $data = EmployeeLeave::findOrFail($id);
            $data->employee_id = $request->employee_id;
            $data->leave_purpose_id = $leave_purpose_id;
            $data->start_date = date('Y-m-d', strtotime($request->start_date));
            $data->end_date = date('Y-m-d', strtotime($request->end_date));
            $data->save();

            return redirect()->route('employee.leave.view')->with([
                'message' => 'Employee Leave Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('LeaveUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Leave update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Delete leave
     */
    public function LeaveDelete($id){
        try {

            $leave = EmployeeLeave::findOrFail($id);
            $leave->delete();

            return redirect()->route('employee.leave.view')->with([
                'message' => 'Employee Leave Deleted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('LeaveDelete Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Leave delete failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
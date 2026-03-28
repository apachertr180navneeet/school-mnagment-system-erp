<?php

namespace App\Http\Controllers\Backend\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmployeeAttendance;
use Illuminate\Support\Facades\Log;

class EmployeeAttendanceController extends Controller
{

    /**
     * 🔹 View attendance list (group by date)
     */
    public function AttendanceView(){
        try {

            $data['allData'] = EmployeeAttendance::select('date')
                                ->groupBy('date')
                                ->orderBy('id', 'DESC')
                                ->get();

            return view('backend.employee.employee_attendance.employee_attendance_view', $data);

        } catch (\Exception $e) {

            Log::error('AttendanceView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load attendance!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show attendance add form
     */
    public function AttendanceAdd(){
        try {

            $data['employees'] = User::where('usertype', 'employee')->get();

            return view('backend.employee.employee_attendance.employee_attendance_add', $data);

        } catch (\Exception $e) {

            Log::error('AttendanceAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store attendance
     */
    public function AttendanceStore(Request $request){
        try {

            $date = date('Y-m-d', strtotime($request->date));

            // Delete existing attendance for that date
            EmployeeAttendance::where('date', $date)->delete();

            // Total employees
            $countemployee = count($request->employee_id);

            for ($i = 0; $i < $countemployee; $i++) {

                $attend_status = 'attend_status'.$i;

                $attend = new EmployeeAttendance();
                $attend->date = $date;
                $attend->employee_id = $request->employee_id[$i];
                $attend->attend_status = $request->$attend_status;
                $attend->save();
            }

            return redirect()->route('employee.attendance.view')->with([
                'message' => 'Employee Attendance Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('AttendanceStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Attendance save failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Edit attendance by date
     */
    public function AttendanceEdit($date){
        try {

            $data['editData'] = EmployeeAttendance::where('date', $date)->get();
            $data['employees'] = User::where('usertype', 'employee')->get();

            return view('backend.employee.employee_attendance.employee_attendance_edit', $data);

        } catch (\Exception $e) {

            Log::error('AttendanceEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load edit page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 View attendance details by date
     */
    public function AttendanceDetails($date){
        try {

            $data['details'] = EmployeeAttendance::where('date', $date)->get();

            return view('backend.employee.employee_attendance.employee_attendance_details', $data);

        } catch (\Exception $e) {

            Log::error('AttendanceDetails Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load details!',
                'alert-type' => 'error'
            ]);
        }
    }

}
<?php

namespace App\Http\Controllers\Backend\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmployeeSallaryLog;
use Illuminate\Support\Facades\Log;

class EmployeeSalaryController extends Controller
{

    /**
     * 🔹 View all employees salary list
     */
    public function SalaryView(){
        try {

            $data['allData'] = User::where('usertype', 'employee')->get();

            return view('backend.employee.employee_salary.employee_salary_view', $data);

        } catch (\Exception $e) {

            Log::error('SalaryView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load salary data!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show salary increment form
     */
    public function SalaryIncrement($id){
        try {

            $data['editData'] = User::findOrFail($id);

            return view('backend.employee.employee_salary.employee_salary_increment', $data);

        } catch (\Exception $e) {

            Log::error('SalaryIncrement Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Employee not found!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store salary increment
     */
    public function SalaryStore(Request $request, $id){
        try {

            $user = User::findOrFail($id);

            $previous_salary = (float)$user->salary;
            $increment = (float)$request->increment_salary;
            $present_salary = $previous_salary + $increment;

            // Update user salary
            $user->salary = $present_salary;
            $user->save();

            // Save salary log
            EmployeeSallaryLog::create([
                'employee_id' => $id,
                'previous_salary' => $previous_salary,
                'increment_salary' => $increment,
                'present_salary' => $present_salary,
                'effected_salary' => date('Y-m-d', strtotime($request->effected_salary))
            ]);

            return redirect()->route('employee.salary.view')->with([
                'message' => 'Salary Incremented Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('SalaryStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Salary update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show salary details & history
     */
    public function SalaryDetails($id){
        try {

            $data['details'] = User::findOrFail($id);
            $data['salary_log'] = EmployeeSallaryLog::where('employee_id', $id)->get();

            return view('backend.employee.employee_salary.employee_salary_details', $data);

        } catch (\Exception $e) {

            Log::error('SalaryDetails Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load salary details!',
                'alert-type' => 'error'
            ]);
        }
    }

}
<?php

namespace App\Http\Controllers\Backend\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use App\Models\AccountEmployeeSalary;
use Illuminate\Support\Facades\Log;

class AccountSalaryController extends Controller
{

    /**
     * 🔹 View salary list
     */
    public function AccountSalaryView(){
        try {

            $data['allData'] = AccountEmployeeSalary::all();
            return view('backend.account.employee_salary.employee_salary_view', $data);

        } catch (\Exception $e) {

            Log::error('AccountSalaryView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load salary data!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add salary page
     */
    public function AccountSalaryAdd(){
        try {

            return view('backend.account.employee_salary.employee_salary_add');

        } catch (\Exception $e) {

            Log::error('AccountSalaryAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Unable to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Get employee salary data via AJAX
     */
    public function AccountSalaryGetEmployee(Request $request){
        try {

            $date = date('Y-m', strtotime($request->date));

            $where = [];
            if ($date != '') {
                $where[] = ['date', 'like', $date.'%'];
            }

            // Get unique employees with attendance
            $data = EmployeeAttendance::select('employee_id')
                        ->groupBy('employee_id')
                        ->with(['user'])
                        ->where($where)
                        ->get();

            // Table Header
            $html['thsource']  = '<th>SL</th>';
            $html['thsource'] .= '<th>ID NO</th>';
            $html['thsource'] .= '<th>Employee Name</th>';
            $html['thsource'] .= '<th>Basic Salary</th>';
            $html['thsource'] .= '<th>Salary This Month</th>';
            $html['thsource'] .= '<th>Select</th>';

            foreach ($data as $key => $attend) {

                // Check if salary already exists
                $account_salary = AccountEmployeeSalary::where('employee_id', $attend->employee_id)
                                    ->where('date', $date)
                                    ->first();

                $checked = ($account_salary != null) ? 'checked' : '';

                // Get total attendance
                $totalattend = EmployeeAttendance::where($where)
                                ->where('employee_id', $attend->employee_id)
                                ->get();

                $absentcount = count($totalattend->where('attend_status', 'Absent'));

                // Salary calculation
                $salary = (float) $attend['user']['salary'];
                $salaryperday = $salary / 30;
                $totalsalaryminus = $absentcount * $salaryperday;
                $totalsalary = $salary - $totalsalaryminus;

                // Build row
                $html[$key]['tdsource']  = '<td>'.($key+1).'</td>';
                $html[$key]['tdsource'] .= '<td>'.$attend['user']['id_no'].'<input type="hidden" name="date" value="'.$date.'"></td>';
                $html[$key]['tdsource'] .= '<td>'.$attend['user']['name'].'</td>';
                $html[$key]['tdsource'] .= '<td>'.$salary.'</td>';
                $html[$key]['tdsource'] .= '<td>'.$totalsalary.'<input type="hidden" name="amount[]" value="'.$totalsalary.'"></td>';
                $html[$key]['tdsource'] .= '<td>
                    <input type="hidden" name="employee_id[]" value="'.$attend->employee_id.'">
                    <input type="checkbox" name="checkmanage[]" value="'.$key.'" '.$checked.' style="transform: scale(1.3);">
                </td>';
            }

            return response()->json($html);

        } catch (\Exception $e) {

            Log::error('AccountSalaryGetEmployee Error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load employee salary data'
            ], 500);
        }
    }


    /**
     * 🔹 Store employee salary
     */
    public function AccountSalaryStore(Request $request){
        try {

            $date = date('Y-m', strtotime($request->date));

            // Delete existing salary for that month
            AccountEmployeeSalary::where('date', $date)->delete();

            $checkdata = $request->checkmanage;

            if ($checkdata != null) {

                foreach ($checkdata as $key) {

                    $data = new AccountEmployeeSalary();
                    $data->date = $date;
                    $data->employee_id = $request->employee_id[$key];
                    $data->amount = $request->amount[$key];
                    $data->save();
                }
            }

            return redirect()->route('account.salary.view')->with([
                'message' => 'Salary Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('AccountSalaryStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Salary update failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
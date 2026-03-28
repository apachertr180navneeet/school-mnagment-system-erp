<?php

namespace App\Http\Controllers\Backend\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use Illuminate\Support\Facades\Log;
use PDF;

class MonthlySalaryController extends Controller
{

    /**
     * 🔹 Show monthly salary page
     */
    public function MonthlySalaryView(){
        try {

            return view('backend.employee.monthly_salary.monthly_salary_view');

        } catch (\Exception $e) {

            Log::error('MonthlySalaryView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Get monthly salary data (AJAX)
     */
    public function MonthlySalaryGet(Request $request){
        try {

            $date = date('Y-m', strtotime($request->date));
            $where = [];

            if ($date != '') {
                $where[] = ['date', 'like', $date.'%'];
            }

            // Get unique employees
            $data = EmployeeAttendance::select('employee_id')
                        ->groupBy('employee_id')
                        ->with(['user'])
                        ->where($where)
                        ->get();

            // Table header
            $html['thsource']  = '<th>SL</th>';
            $html['thsource'] .= '<th>Employee Name</th>';
            $html['thsource'] .= '<th>Basic Salary</th>';
            $html['thsource'] .= '<th>Salary This Month</th>';
            $html['thsource'] .= '<th>Action</th>';

            foreach ($data as $key => $attend) {

                // Get attendance for each employee
                $totalattend = EmployeeAttendance::where($where)
                                    ->where('employee_id', $attend->employee_id)
                                    ->get();

                $absentcount = count($totalattend->where('attend_status', 'Absent'));

                $salary = (float) $attend['user']['salary'];
                $salaryperday = $salary / 30;
                $totalsalaryminus = $absentcount * $salaryperday;
                $totalsalary = $salary - $totalsalaryminus;

                // Build row
                $html[$key]['tdsource']  = '<td>'.($key+1).'</td>';
                $html[$key]['tdsource'] .= '<td>'.$attend['user']['name'].'</td>';
                $html[$key]['tdsource'] .= '<td>'.$salary.'</td>';
                $html[$key]['tdsource'] .= '<td>'.$totalsalary.'</td>';
                $html[$key]['tdsource'] .= '<td>
                    <a class="btn btn-sm btn-success" target="_blank"
                    href="'.route("employee.monthly.salary.payslip", $attend->employee_id).'">
                    Payslip</a>
                </td>';
            }

            return response()->json($html);

        } catch (\Exception $e) {

            Log::error('MonthlySalaryGet Error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load salary data'
            ], 500);
        }
    }


    /**
     * 🔹 Generate monthly salary PDF
     */
    public function MonthlySalaryPayslip(Request $request, $employee_id){
        try {

            // Get any record to determine month
            $attendance = EmployeeAttendance::where('employee_id', $employee_id)->firstOrFail();

            $date = date('Y-m', strtotime($attendance->date));
            $where = [];

            if ($date != '') {
                $where[] = ['date', 'like', $date.'%'];
            }

            // Get employee attendance details
            $data['details'] = EmployeeAttendance::with(['user'])
                                ->where($where)
                                ->where('employee_id', $employee_id)
                                ->get();

            // Generate PDF
            $pdf = PDF::loadView('backend.employee.monthly_salary.monthly_salary_pdf', $data);
            $pdf->SetProtection(['copy', 'print'], '', 'pass');

            return $pdf->stream('monthly_salary_slip.pdf');

        } catch (\Exception $e) {

            Log::error('MonthlySalaryPayslip Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to generate payslip!',
                'alert-type' => 'error'
            ]);
        }
    }

}
<?php

namespace App\Http\Controllers\Backend\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountEmployeeSalary;
use App\Models\AccountOtherCost;
use App\Models\AccountStudentFee;
use Illuminate\Support\Facades\Log;
use PDF;

class ProfiteController extends Controller
{

    /**
     * 🔹 Show profit report page
     */
    public function MonthlyProfitView(){
        try {

            return view('backend.report.profit.profit_view');

        } catch (\Exception $e) {

            Log::error('MonthlyProfitView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Get profit data (AJAX)
     */
    public function MonthlyProfitDatewais(Request $request){
        try {

            // Format dates
            $start_date = date('Y-m', strtotime($request->start_date));
            $end_date   = date('Y-m', strtotime($request->end_date));

            $sdate = date('Y-m-d', strtotime($request->start_date));
            $edate = date('Y-m-d', strtotime($request->end_date));

            // Income
            $student_fee = AccountStudentFee::whereBetween('date', [$start_date, $end_date])->sum('amount');

            // Expenses
            $other_cost = AccountOtherCost::whereBetween('date', [$sdate, $edate])->sum('amount');
            $emp_salary = AccountEmployeeSalary::whereBetween('date', [$start_date, $end_date])->sum('amount');

            // Calculation
            $total_cost = $other_cost + $emp_salary;
            $profit = $student_fee - $total_cost;

            // Table header
            $html['thsource']  = '<th>Student Fee</th>';
            $html['thsource'] .= '<th>Other Cost</th>';
            $html['thsource'] .= '<th>Employee Salary</th>';
            $html['thsource'] .= '<th>Total Cost</th>';
            $html['thsource'] .= '<th>Profit</th>';
            $html['thsource'] .= '<th>Action</th>';

            // Table row
            $html['tdsource']  = '<td>'.$student_fee.'</td>';
            $html['tdsource'] .= '<td>'.$other_cost.'</td>';
            $html['tdsource'] .= '<td>'.$emp_salary.'</td>';
            $html['tdsource'] .= '<td>'.$total_cost.'</td>';
            $html['tdsource'] .= '<td>'.$profit.'</td>';
            $html['tdsource'] .= '<td>
                <a class="btn btn-sm btn-success" target="_blank"
                href="'.route("report.profit.pdf").'?start_date='.$sdate.'&end_date='.$edate.'">
                Download PDF</a>
            </td>';

            return response()->json($html);

        } catch (\Exception $e) {

            Log::error('MonthlyProfitDatewais Error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to calculate profit'
            ], 500);
        }
    }


    /**
     * 🔹 Generate profit report PDF
     */
    public function MonthlyProfitPdf(Request $request){
        try {

            $data['start_date'] = date('Y-m', strtotime($request->start_date));
            $data['end_date']   = date('Y-m', strtotime($request->end_date));
            $data['sdate']      = date('Y-m-d', strtotime($request->start_date));
            $data['edate']      = date('Y-m-d', strtotime($request->end_date));

            $pdf = PDF::loadView('backend.report.profit.profit_pdf', $data);
            $pdf->SetProtection(['copy', 'print'], '', 'pass');

            return $pdf->stream('profit_report.pdf');

        } catch (\Exception $e) {

            Log::error('MonthlyProfitPdf Error: '.$e->getMessage());

            return back()->with([
                'message' => 'PDF generation failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
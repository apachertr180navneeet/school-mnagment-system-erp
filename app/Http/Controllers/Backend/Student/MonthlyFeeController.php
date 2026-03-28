<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignStudent;
use App\Models\FeeCategoryAmount;
use App\Models\StudentYear;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Log;
use PDF;

class MonthlyFeeController extends Controller
{

    /**
     * 🔹 Show monthly fee page
     */
    public function MonthlyFeeView(){
        try {

            $data['years'] = StudentYear::all();
            $data['classes'] = StudentClass::all();

            return view('backend.student.monthly_fee.monthly_fee_view', $data);

        } catch (\Exception $e) {

            Log::error('MonthlyFeeView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Get student monthly fee data (AJAX)
     */
    public function MonthlyFeeClassData(Request $request){
        try {

            $year_id = $request->year_id;
            $class_id = $request->class_id;

            $where = [];

            if ($year_id != '') {
                $where[] = ['year_id', 'like', $year_id.'%'];
            }

            if ($class_id != '') {
                $where[] = ['class_id', 'like', $class_id.'%'];
            }

            // Fetch students
            $allStudent = AssignStudent::with(['discount'])
                            ->where($where)
                            ->get();

            // Table header
            $html['thsource']  = '<th>SL</th>';
            $html['thsource'] .= '<th>ID No</th>';
            $html['thsource'] .= '<th>Student Name</th>';
            $html['thsource'] .= '<th>Roll No</th>';
            $html['thsource'] .= '<th>Monthly Fee</th>';
            $html['thsource'] .= '<th>Discount</th>';
            $html['thsource'] .= '<th>Final Fee</th>';
            $html['thsource'] .= '<th>Action</th>';

            foreach ($allStudent as $key => $v) {

                // Get monthly fee (category_id = 2)
                $registrationfee = FeeCategoryAmount::where('fee_category_id', 2)
                                    ->where('class_id', $v->class_id)
                                    ->first();

                if (!$registrationfee) continue;

                $originalfee = $registrationfee->amount;
                $discount = $v['discount']['discount'] ?? 0;
                $discountAmount = ($discount / 100) * $originalfee;
                $finalfee = $originalfee - $discountAmount;

                // Build row
                $html[$key]['tdsource']  = '<td>'.($key+1).'</td>';
                $html[$key]['tdsource'] .= '<td>'.$v['student']['id_no'].'</td>';
                $html[$key]['tdsource'] .= '<td>'.$v['student']['name'].'</td>';
                $html[$key]['tdsource'] .= '<td>'.$v->roll.'</td>';
                $html[$key]['tdsource'] .= '<td>'.$originalfee.'</td>';
                $html[$key]['tdsource'] .= '<td>'.$discount.'%</td>';
                $html[$key]['tdsource'] .= '<td>'.$finalfee.'</td>';
                $html[$key]['tdsource'] .= '<td>
                    <a class="btn btn-sm btn-success" target="_blank"
                    href="'.route("student.monthly.fee.payslip").'?class_id='.$v->class_id.'&student_id='.$v->student_id.'&month='.$request->month.'">
                    Fee Slip</a>
                </td>';
            }

            return response()->json($html);

        } catch (\Exception $e) {

            Log::error('MonthlyFeeClassData Error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load data'
            ], 500);
        }
    }


    /**
     * 🔹 Generate monthly fee PDF slip
     */
    public function MonthlyFeePayslip(Request $request){
        try {

            $student_id = $request->student_id;
            $class_id = $request->class_id;

            // Pass selected month
            $data['month'] = $request->month;

            // Get student details
            $data['details'] = AssignStudent::with(['student','discount'])
                                ->where('student_id', $student_id)
                                ->where('class_id', $class_id)
                                ->firstOrFail();

            // Generate PDF
            $pdf = PDF::loadView('backend.student.monthly_fee.monthly_fee_pdf', $data);
            $pdf->SetProtection(['copy', 'print'], '', 'pass');

            return $pdf->stream('monthly_fee_slip.pdf');

        } catch (\Exception $e) {

            Log::error('MonthlyFeePayslip Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to generate PDF!',
                'alert-type' => 'error'
            ]);
        }
    }

}
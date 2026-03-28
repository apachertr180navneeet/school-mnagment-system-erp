<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignStudent;
use App\Models\FeeCategoryAmount;
use App\Models\StudentYear;
use App\Models\StudentClass;
use App\Models\ExamType;
use Illuminate\Support\Facades\Log;
use PDF;

class ExamFeeController extends Controller
{

    /**
     * 🔹 View exam fee page
     */
    public function ExamFeeView(){
        try {

            $data['years'] = StudentYear::all();
            $data['classes'] = StudentClass::all();
            $data['exam_type'] = ExamType::all();

            return view('backend.student.exam_fee.exam_fee_view', $data);

        } catch (\Exception $e) {

            Log::error('ExamFeeView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Get students exam fee data (AJAX)
     */
    public function ExamFeeClassData(Request $request){
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

            // Fetch students with discount
            $allStudent = AssignStudent::with(['discount'])
                            ->where($where)
                            ->get();

            // Table header
            $html['thsource']  = '<th>SL</th>';
            $html['thsource'] .= '<th>ID No</th>';
            $html['thsource'] .= '<th>Student Name</th>';
            $html['thsource'] .= '<th>Roll No</th>';
            $html['thsource'] .= '<th>Exam Fee</th>';
            $html['thsource'] .= '<th>Discount</th>';
            $html['thsource'] .= '<th>Final Fee</th>';
            $html['thsource'] .= '<th>Action</th>';

            foreach ($allStudent as $key => $v) {

                // Get exam fee (category_id = 3)
                $registrationfee = FeeCategoryAmount::where('fee_category_id', 3)
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
                    href="'.route("student.exam.fee.payslip").'?class_id='.$v->class_id.'&student_id='.$v->student_id.'&exam_type_id='.$request->exam_type_id.'">
                    Fee Slip</a>
                </td>';
            }

            return response()->json($html);

        } catch (\Exception $e) {

            Log::error('ExamFeeClassData Error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load data'
            ], 500);
        }
    }


    /**
     * 🔹 Generate exam fee PDF slip
     */
    public function ExamFeePayslip(Request $request){
        try {

            $student_id = $request->student_id;
            $class_id = $request->class_id;

            // Get exam type name
            $data['exam_type'] = ExamType::where('id', $request->exam_type_id)->value('name');

            // Get student details
            $data['details'] = AssignStudent::with(['student','discount'])
                                ->where('student_id', $student_id)
                                ->where('class_id', $class_id)
                                ->firstOrFail();

            // Generate PDF
            $pdf = PDF::loadView('backend.student.exam_fee.exam_fee_pdf', $data);
            $pdf->SetProtection(['copy', 'print'], '', 'pass');

            return $pdf->stream('exam_fee_slip.pdf');

        } catch (\Exception $e) {

            Log::error('ExamFeePayslip Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to generate PDF!',
                'alert-type' => 'error'
            ]);
        }
    }

}
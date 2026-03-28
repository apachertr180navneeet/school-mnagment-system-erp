<?php

namespace App\Http\Controllers\Backend\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignStudent;
use App\Models\FeeCategoryAmount;
use App\Models\StudentYear;
use App\Models\StudentClass;
use App\Models\FeeCategory;
use App\Models\AccountStudentFee;
use Illuminate\Support\Facades\Log;

class StudentFeeController extends Controller
{

    /**
     * 🔹 View all student fee records
     */
    public function StudentFeeView(){
        try {

            $data['allData'] = AccountStudentFee::all();
            return view('backend.account.student_fee.student_fee_view', $data);

        } catch (\Exception $e) {

            Log::error('StudentFeeView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load data!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add student fee form
     */
    public function StudentFeeAdd(){
        try {

            $data['years'] = StudentYear::all();
            $data['classes'] = StudentClass::all();
            $data['fee_categories'] = FeeCategory::all();

            return view('backend.account.student_fee.student_fee_add', $data);

        } catch (\Exception $e) {

            Log::error('StudentFeeAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Unable to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Get students and calculate fee (AJAX)
     */
    public function StudentFeeGetStudent(Request $request){
        try {

            $year_id = $request->year_id;
            $class_id = $request->class_id;
            $fee_category_id = $request->fee_category_id;
            $date = date('Y-m', strtotime($request->date));

            // Get students with discount
            $data = AssignStudent::with(['discount'])
                        ->where('year_id', $year_id)
                        ->where('class_id', $class_id)
                        ->get();

            // Table header
            $html['thsource']  = '<th>ID No</th>';
            $html['thsource'] .= '<th>Student Name</th>';
            $html['thsource'] .= '<th>Father Name</th>';
            $html['thsource'] .= '<th>Original Fee</th>';
            $html['thsource'] .= '<th>Discount</th>';
            $html['thsource'] .= '<th>Final Fee</th>';
            $html['thsource'] .= '<th>Select</th>';

            foreach ($data as $key => $std) {

                // Get fee amount
                $registrationfee = FeeCategoryAmount::where('fee_category_id', $fee_category_id)
                                    ->where('class_id', $std->class_id)
                                    ->first();

                if (!$registrationfee) continue;

                // Check if already paid
                $accountstudentfees = AccountStudentFee::where('student_id', $std->student_id)
                    ->where('year_id', $std->year_id)
                    ->where('class_id', $std->class_id)
                    ->where('fee_category_id', $fee_category_id)
                    ->where('date', $date)
                    ->first();

                $checked = ($accountstudentfees) ? 'checked' : '';

                // Calculate fee
                $originalFee = $registrationfee->amount;
                $discount = $std['discount']['discount'] ?? 0;
                $discountAmount = ($discount / 100) * $originalFee;
                $finalFee = (int)$originalFee - (int)$discountAmount;

                // Build row
                $html[$key]['tdsource']  = '<td>'.$std['student']['id_no'].'<input type="hidden" name="fee_category_id" value="'.$fee_category_id.'"></td>';
                $html[$key]['tdsource'] .= '<td>'.$std['student']['name'].'<input type="hidden" name="year_id" value="'.$std->year_id.'"></td>';
                $html[$key]['tdsource'] .= '<td>'.$std['student']['fname'].'<input type="hidden" name="class_id" value="'.$std->class_id.'"></td>';
                $html[$key]['tdsource'] .= '<td>'.$originalFee.'<input type="hidden" name="date" value="'.$date.'"></td>';
                $html[$key]['tdsource'] .= '<td>'.$discount.'%</td>';
                $html[$key]['tdsource'] .= '<td><input type="text" name="amount[]" value="'.$finalFee.'" class="form-control" readonly></td>';
                $html[$key]['tdsource'] .= '<td>
                    <input type="hidden" name="student_id[]" value="'.$std->student_id.'">
                    <input type="checkbox" name="checkmanage[]" value="'.$key.'" '.$checked.'>
                </td>';
            }

            return response()->json($html);

        } catch (\Exception $e) {

            Log::error('StudentFeeGetStudent Error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load students'
            ], 500);
        }
    }


    /**
     * 🔹 Store student fee data
     */
    public function StudentFeeStore(Request $request){
        try {

            $date = date('Y-m', strtotime($request->date));

            // Delete old records
            AccountStudentFee::where('year_id', $request->year_id)
                ->where('class_id', $request->class_id)
                ->where('fee_category_id', $request->fee_category_id)
                ->where('date', $request->date)
                ->delete();

            $checkdata = $request->checkmanage;

            if ($checkdata != null) {

                foreach ($checkdata as $key) {

                    $data = new AccountStudentFee();
                    $data->year_id = $request->year_id;
                    $data->class_id = $request->class_id;
                    $data->date = $date;
                    $data->fee_category_id = $request->fee_category_id;
                    $data->student_id = $request->student_id[$key];
                    $data->amount = $request->amount[$key];
                    $data->save();
                }
            }

            return redirect()->route('student.fee.view')->with([
                'message' => 'Student Fee Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('StudentFeeStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Fee save failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
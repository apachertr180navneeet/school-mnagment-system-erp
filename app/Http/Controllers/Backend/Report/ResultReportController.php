<?php

namespace App\Http\Controllers\Backend\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentYear;
use App\Models\StudentClass;
use App\Models\ExamType;
use App\Models\StudentMarks;
use App\Models\AssignStudent;
use Illuminate\Support\Facades\Log;
use PDF;

class ResultReportController extends Controller
{

    /**
     * 🔹 Show result report page
     */
    public function ResultView(){
        try {

            $data['years'] = StudentYear::all();
            $data['classes'] = StudentClass::all();
            $data['exam_type'] = ExamType::all();

            return view('backend.report.student_result.student_result_view', $data);

        } catch (\Exception $e) {

            Log::error('ResultView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Generate student result PDF
     */
    public function ResultGet(Request $request){
        try {

            $year_id = $request->year_id;
            $class_id = $request->class_id;
            $exam_type_id = $request->exam_type_id;

            // Get all results
            $results = StudentMarks::where('year_id', $year_id)
                ->where('class_id', $class_id)
                ->where('exam_type_id', $exam_type_id)
                ->get();

            // Check data exists
            if ($results->isEmpty()) {

                return back()->with([
                    'message' => 'No result found for selected criteria!',
                    'alert-type' => 'error'
                ]);
            }

            // Grouped data
            $data['allData'] = StudentMarks::select('year_id','class_id','exam_type_id','student_id')
                ->where('year_id', $year_id)
                ->where('class_id', $class_id)
                ->where('exam_type_id', $exam_type_id)
                ->groupBy('year_id')
                ->groupBy('class_id')
                ->groupBy('exam_type_id')
                ->groupBy('student_id')
                ->get();

            // Generate PDF
            $pdf = PDF::loadView('backend.report.student_result.student_result_pdf', $data);
            $pdf->SetProtection(['copy', 'print'], '', 'pass');

            return $pdf->stream('student_result.pdf');

        } catch (\Exception $e) {

            Log::error('ResultGet Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Result generation failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show ID card filter page
     */
    public function IdcardView(){
        try {

            $data['years'] = StudentYear::all();
            $data['classes'] = StudentClass::all();

            return view('backend.report.idcard.idcard_view', $data);

        } catch (\Exception $e) {

            Log::error('IdcardView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Generate student ID card PDF
     */
    public function IdcardGet(Request $request){
        try {

            $year_id = $request->year_id;
            $class_id = $request->class_id;

            // Get students
            $students = AssignStudent::where('year_id', $year_id)
                ->where('class_id', $class_id)
                ->get();

            // Check data exists
            if ($students->isEmpty()) {

                return back()->with([
                    'message' => 'No student found for selected criteria!',
                    'alert-type' => 'error'
                ]);
            }

            $data['allData'] = $students;

            // Generate PDF
            $pdf = PDF::loadView('backend.report.idcard.idcard_pdf', $data);
            $pdf->SetProtection(['copy', 'print'], '', 'pass');

            return $pdf->stream('student_id_cards.pdf');

        } catch (\Exception $e) {

            Log::error('IdcardGet Error: '.$e->getMessage());

            return back()->with([
                'message' => 'ID card generation failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
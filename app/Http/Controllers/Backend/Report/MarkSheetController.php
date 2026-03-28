<?php

namespace App\Http\Controllers\Backend\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentMarks;
use App\Models\ExamType;
use App\Models\StudentClass;
use App\Models\StudentYear;
use App\Models\MarksGrade;
use Illuminate\Support\Facades\Log;

class MarkSheetController extends Controller
{

    /**
     * 🔹 Show marksheet filter page
     */
    public function MarkSheetView(){
        try {

            $data['years'] = StudentYear::orderBy('id', 'desc')->get();
            $data['classes'] = StudentClass::all();
            $data['exam_type'] = ExamType::all();

            return view('backend.report.marksheet.marksheet_view', $data);

        } catch (\Exception $e) {

            Log::error('MarkSheetView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Generate marksheet
     */
    public function MarkSheetGet(Request $request){
        try {

            $year_id = $request->year_id;
            $class_id = $request->class_id;
            $exam_type_id = $request->exam_type_id;
            $id_no = $request->id_no;

            // Get all marks for student
            $allMarks = StudentMarks::with(['assign_subject','year'])
                ->where('year_id', $year_id)
                ->where('class_id', $class_id)
                ->where('exam_type_id', $exam_type_id)
                ->where('id_no', $id_no)
                ->get();

            // Check if data exists
            if ($allMarks->isEmpty()) {

                return back()->with([
                    'message' => 'No data found for given criteria!',
                    'alert-type' => 'error'
                ]);
            }

            // Count failed subjects
            $count_fail = $allMarks->where('marks', '<', 33)->count();

            // Get grading system
            $allGrades = MarksGrade::all();

            return view('backend.report.marksheet.marksheet_pdf', compact(
                'allMarks',
                'allGrades',
                'count_fail'
            ));

        } catch (\Exception $e) {

            Log::error('MarkSheetGet Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Marksheet generation failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
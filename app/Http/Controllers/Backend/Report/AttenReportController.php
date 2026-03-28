<?php

namespace App\Http\Controllers\Backend\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmployeeAttendance;
use Illuminate\Support\Facades\Log;
use PDF;

class AttenReportController extends Controller
{

    /**
     * 🔹 Show attendance report page
     */
    public function AttenReportView(){
        try {

            $data['employees'] = User::where('usertype', 'employee')->get();

            return view('backend.report.attend_report.attend_report_view', $data);

        } catch (\Exception $e) {

            Log::error('AttenReportView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Generate attendance report PDF
     */
    public function AttenReportGet(Request $request){
        try {

            $where = [];

            // Filter by employee
            if (!empty($request->employee_id)) {
                $where[] = ['employee_id', $request->employee_id];
            }

            // Filter by month
            $date = date('Y-m', strtotime($request->date));
            if (!empty($date)) {
                $where[] = ['date', 'like', $date.'%'];
            }

            // Get attendance data
            $attendance = EmployeeAttendance::with(['user'])
                            ->where($where)
                            ->get();

            // Check data exists
            if ($attendance->isEmpty()) {

                return back()->with([
                    'message' => 'No data found for selected criteria!',
                    'alert-type' => 'error'
                ]);
            }

            // Prepare data
            $data['allData'] = $attendance;

            $data['absents'] = $attendance->where('attend_status', 'Absent')->count();
            $data['leaves']  = $attendance->where('attend_status', 'Leave')->count();
            $data['month']   = date('m-Y', strtotime($request->date));

            // Generate PDF
            $pdf = PDF::loadView('backend.report.attend_report.attend_report_pdf', $data);
            $pdf->SetProtection(['copy', 'print'], '', 'pass');

            return $pdf->stream('attendance_report.pdf');

        } catch (\Exception $e) {

            Log::error('AttenReportGet Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Report generation failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
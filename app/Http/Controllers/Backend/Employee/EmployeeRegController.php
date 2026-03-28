<?php

namespace App\Http\Controllers\Backend\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Designation;
use App\Models\EmployeeSallaryLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;

class EmployeeRegController extends Controller
{

    /**
     * 🔹 View all employees
     */
    public function EmployeeView(){
        try {

            $data['allData'] = User::where('usertype','Employee')->get();

            return view('backend.employee.employee_reg.employee_view', $data);

        } catch (\Exception $e) {

            Log::error('EmployeeView Error: '.$e->getMessage());

            return back()->with(['message'=>'Failed to load employees','alert-type'=>'error']);
        }
    }


    /**
     * 🔹 Show add employee form
     */
    public function EmployeeAdd(){
        try {

            $data['designation'] = Designation::all();

            return view('backend.employee.employee_reg.employee_add', $data);

        } catch (\Exception $e) {

            Log::error('EmployeeAdd Error: '.$e->getMessage());

            return back()->with(['message'=>'Page load failed','alert-type'=>'error']);
        }
    }


    /**
     * 🔹 Store employee (transaction)
     */
    public function EmployeeStore(Request $request){
        try {

            DB::transaction(function() use($request){

                // Generate employee ID
                $yearMonth = date('Ym', strtotime($request->join_date));
                $last = User::where('usertype','employee')->latest()->first();
                $id = $last ? $last->id + 1 : 1;

                $id_no = str_pad($id, 4, '0', STR_PAD_LEFT);
                $final_id_no = $yearMonth.$id_no;

                // Create user
                $user = new User();
                $code = rand(1000,9999);

                $user->id_no = $final_id_no;
                $user->password = bcrypt($code);
                $user->usertype = 'employee';
                $user->code = $code;
                $user->name = $request->name;
                $user->fname = $request->fname;
                $user->mname = $request->mname;
                $user->mobile = $request->mobile;
                $user->address = $request->address;
                $user->gender = $request->gender;
                $user->religion = $request->religion;
                $user->salary = $request->salary;
                $user->designation_id = $request->designation_id;
                $user->dob = date('Y-m-d', strtotime($request->dob));
                $user->join_date = date('Y-m-d', strtotime($request->join_date));

                // Image upload
                if ($request->file('image')) {
                    $file = $request->file('image');
                    $filename = date('YmdHi').'_'.$file->getClientOriginalName();
                    $file->move(public_path('upload/employee_images'), $filename);
                    $user->image = $filename;
                }

                $user->save();

                // Salary log
                EmployeeSallaryLog::create([
                    'employee_id' => $user->id,
                    'effected_salary' => date('Y-m-d', strtotime($request->join_date)),
                    'previous_salary' => $request->salary,
                    'present_salary' => $request->salary,
                    'increment_salary' => 0
                ]);
            });

            return redirect()->route('employee.registration.view')->with([
                'message'=>'Employee Registered Successfully',
                'alert-type'=>'success'
            ]);

        } catch (\Exception $e) {

            Log::error('EmployeeStore Error: '.$e->getMessage());

            return back()->with(['message'=>'Employee creation failed','alert-type'=>'error']);
        }
    }


    /**
     * 🔹 Edit employee
     */
    public function EmployeeEdit($id){
        try {

            $data['editData'] = User::findOrFail($id);
            $data['designation'] = Designation::all();

            return view('backend.employee.employee_reg.employee_edit', $data);

        } catch (\Exception $e) {

            Log::error('EmployeeEdit Error: '.$e->getMessage());

            return back()->with(['message'=>'Employee not found','alert-type'=>'error']);
        }
    }


    /**
     * 🔹 Update employee
     */
    public function EmployeeUpdate(Request $request, $id){
        try {

            $user = User::findOrFail($id);

            $user->update([
                'name' => $request->name,
                'fname' => $request->fname,
                'mname' => $request->mname,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'gender' => $request->gender,
                'religion' => $request->religion,
                'designation_id' => $request->designation_id,
            ]);

            $user->dob = date('Y-m-d', strtotime($request->dob));

            // Image update
            if ($request->file('image')) {

                if (!empty($user->image) && file_exists(public_path('upload/employee_images/'.$user->image))) {
                    unlink(public_path('upload/employee_images/'.$user->image));
                }

                $file = $request->file('image');
                $filename = date('YmdHi').'_'.$file->getClientOriginalName();
                $file->move(public_path('upload/employee_images'), $filename);
                $user->image = $filename;
            }

            $user->save();

            return redirect()->route('employee.registration.view')->with([
                'message'=>'Employee Updated Successfully',
                'alert-type'=>'success'
            ]);

        } catch (\Exception $e) {

            Log::error('EmployeeUpdate Error: '.$e->getMessage());

            return back()->with(['message'=>'Update failed','alert-type'=>'error']);
        }
    }


    /**
     * 🔹 Employee details PDF
     */
    public function EmployeeDetails($id){
        try {

            $data['details'] = User::findOrFail($id);

            $pdf = PDF::loadView('backend.employee.employee_reg.employee_details_pdf', $data);
            $pdf->SetProtection(['copy','print'],'','pass');

            return $pdf->stream('employee_details.pdf');

        } catch (\Exception $e) {

            Log::error('EmployeeDetails Error: '.$e->getMessage());

            return back()->with(['message'=>'PDF generation failed','alert-type'=>'error']);
        }
    }

}
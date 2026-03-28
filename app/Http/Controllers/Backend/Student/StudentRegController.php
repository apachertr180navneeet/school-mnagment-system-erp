<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignStudent;
use App\Models\User;
use App\Models\DiscountStudent;
use App\Models\StudentYear;
use App\Models\StudentClass;
use App\Models\StudentGroup;
use App\Models\StudentShift;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;

class StudentRegController extends Controller
{

    /**
     * 🔹 View student list
     */
    public function StudentRegView(){
        try {

            $data['years'] = StudentYear::all();
            $data['classes'] = StudentClass::all();

            $data['year_id'] = StudentYear::latest()->first()->id;
            $data['class_id'] = StudentClass::latest()->first()->id;

            $data['allData'] = AssignStudent::where('year_id', $data['year_id'])
                                ->where('class_id', $data['class_id'])
                                ->get();

            return view('backend.student.student_reg.student_view', $data);

        } catch (\Exception $e) {
            Log::error('StudentRegView Error: '.$e->getMessage());

            return back()->with(['message'=>'Error loading data','alert-type'=>'error']);
        }
    }


    /**
     * 🔹 Filter by class & year
     */
    public function StudentClassYearWise(Request $request){
        try {

            $data['years'] = StudentYear::all();
            $data['classes'] = StudentClass::all();

            $data['year_id'] = $request->year_id;
            $data['class_id'] = $request->class_id;

            $data['allData'] = AssignStudent::where('year_id', $request->year_id)
                                ->where('class_id', $request->class_id)
                                ->get();

            return view('backend.student.student_reg.student_view', $data);

        } catch (\Exception $e) {
            Log::error('StudentClassYearWise Error: '.$e->getMessage());

            return back()->with(['message'=>'Filter failed','alert-type'=>'error']);
        }
    }


    /**
     * 🔹 Show add student form
     */
    public function StudentRegAdd(){
        try {

            return view('backend.student.student_reg.student_add', [
                'years' => StudentYear::all(),
                'classes' => StudentClass::all(),
                'groups' => StudentGroup::all(),
                'shifts' => StudentShift::all(),
            ]);

        } catch (\Exception $e) {
            Log::error('StudentRegAdd Error: '.$e->getMessage());

            return back()->with(['message'=>'Page load failed','alert-type'=>'error']);
        }
    }


    /**
     * 🔹 Store student (with transaction)
     */
    public function StudentRegStore(Request $request){
        try {

            DB::transaction(function() use($request){

                // Generate ID
                $year = StudentYear::find($request->year_id)->name;
                $last = User::where('usertype','Student')->latest()->first();
                $id = $last ? $last->id + 1 : 1;

                $id_no = str_pad($id, 4, '0', STR_PAD_LEFT);
                $final_id_no = $year.$id_no;

                // Create user
                $user = new User();
                $code = rand(1000,9999);

                $user->id_no = $final_id_no;
                $user->password = bcrypt($code);
                $user->usertype = 'Student';
                $user->code = $code;
                $user->name = $request->name;
                $user->fname = $request->fname;
                $user->mname = $request->mname;
                $user->mobile = $request->mobile;
                $user->address = $request->address;
                $user->gender = $request->gender;
                $user->religion = $request->religion;
                $user->dob = date('Y-m-d', strtotime($request->dob));

                if ($request->file('image')) {
                    $file = $request->file('image');
                    $filename = date('YmdHi').'_'.$file->getClientOriginalName();
                    $file->move(public_path('upload/student_images'), $filename);
                    $user->image = $filename;
                }

                $user->save();

                // Assign student
                $assign = new AssignStudent();
                $assign->student_id = $user->id;
                $assign->year_id = $request->year_id;
                $assign->class_id = $request->class_id;
                $assign->group_id = $request->group_id;
                $assign->shift_id = $request->shift_id;
                $assign->save();

                // Discount
                DiscountStudent::create([
                    'assign_student_id' => $assign->id,
                    'fee_category_id' => 1,
                    'discount' => $request->discount
                ]);

            });

            return redirect()->route('student.registration.view')
                ->with(['message'=>'Student Registered Successfully','alert-type'=>'success']);

        } catch (\Exception $e) {

            Log::error('StudentRegStore Error: '.$e->getMessage());

            return back()->with(['message'=>'Registration failed','alert-type'=>'error']);
        }
    }


    /**
     * 🔹 Edit student
     */
    public function StudentRegEdit($student_id){
        try {

            $data = [
                'years' => StudentYear::all(),
                'classes' => StudentClass::all(),
                'groups' => StudentGroup::all(),
                'shifts' => StudentShift::all(),
                'editData' => AssignStudent::with(['student','discount'])
                                ->where('student_id',$student_id)
                                ->firstOrFail()
            ];

            return view('backend.student.student_reg.student_edit', $data);

        } catch (\Exception $e) {
            Log::error('StudentRegEdit Error: '.$e->getMessage());

            return back()->with(['message'=>'Edit failed','alert-type'=>'error']);
        }
    }


    /**
     * 🔹 Update student
     */
    public function StudentRegUpdate(Request $request,$student_id){
        try {

            DB::transaction(function() use($request,$student_id){

                $user = User::findOrFail($student_id);

                $user->update($request->only([
                    'name','fname','mname','mobile','address','gender','religion'
                ]));

                $user->dob = date('Y-m-d', strtotime($request->dob));

                if ($request->file('image')) {
                    if (!empty($user->image) && file_exists(public_path('upload/student_images/'.$user->image))) {
                        unlink(public_path('upload/student_images/'.$user->image));
                    }

                    $file = $request->file('image');
                    $filename = date('YmdHi').'_'.$file->getClientOriginalName();
                    $file->move(public_path('upload/student_images'), $filename);
                    $user->image = $filename;
                }

                $user->save();

                AssignStudent::where('id',$request->id)
                    ->update([
                        'year_id'=>$request->year_id,
                        'class_id'=>$request->class_id,
                        'group_id'=>$request->group_id,
                        'shift_id'=>$request->shift_id
                    ]);

                DiscountStudent::where('assign_student_id',$request->id)
                    ->update(['discount'=>$request->discount]);

            });

            return redirect()->route('student.registration.view')
                ->with(['message'=>'Student Updated Successfully','alert-type'=>'success']);

        } catch (\Exception $e) {
            Log::error('StudentRegUpdate Error: '.$e->getMessage());

            return back()->with(['message'=>'Update failed','alert-type'=>'error']);
        }
    }


    /**
     * 🔹 Promotion view
     */
    public function StudentRegPromotion($student_id){
        try {

            return view('backend.student.student_reg.student_promotion', [
                'years'=>StudentYear::all(),
                'classes'=>StudentClass::all(),
                'groups'=>StudentGroup::all(),
                'shifts'=>StudentShift::all(),
                'editData'=>AssignStudent::with(['student','discount'])
                    ->where('student_id',$student_id)->firstOrFail()
            ]);

        } catch (\Exception $e) {
            Log::error('StudentRegPromotion Error: '.$e->getMessage());

            return back()->with(['message'=>'Promotion page failed','alert-type'=>'error']);
        }
    }


    /**
     * 🔹 Promotion update
     */
    public function StudentUpdatePromotion(Request $request,$student_id){
        try {

            DB::transaction(function() use($request,$student_id){

                $user = User::findOrFail($student_id);

                $user->update($request->only([
                    'name','fname','mname','mobile','address','gender','religion'
                ]));

                $user->dob = date('Y-m-d', strtotime($request->dob));
                $user->save();

                $assign = AssignStudent::create([
                    'student_id'=>$student_id,
                    'year_id'=>$request->year_id,
                    'class_id'=>$request->class_id,
                    'group_id'=>$request->group_id,
                    'shift_id'=>$request->shift_id
                ]);

                DiscountStudent::create([
                    'assign_student_id'=>$assign->id,
                    'fee_category_id'=>1,
                    'discount'=>$request->discount
                ]);

            });

            return redirect()->route('student.registration.view')
                ->with(['message'=>'Student Promoted Successfully','alert-type'=>'success']);

        } catch (\Exception $e) {
            Log::error('StudentUpdatePromotion Error: '.$e->getMessage());

            return back()->with(['message'=>'Promotion failed','alert-type'=>'error']);
        }
    }


    /**
     * 🔹 Student details PDF
     */
    public function StudentRegDetails($student_id){
        try {

            $data['details'] = AssignStudent::with(['student','discount'])
                                ->where('student_id',$student_id)
                                ->firstOrFail();

            $pdf = PDF::loadView('backend.student.student_reg.student_details_pdf', $data);
            $pdf->SetProtection(['copy','print'],'','pass');

            return $pdf->stream('student_details.pdf');

        } catch (\Exception $e) {
            Log::error('StudentRegDetails Error: '.$e->getMessage());

            return back()->with(['message'=>'PDF failed','alert-type'=>'error']);
        }
    }

}
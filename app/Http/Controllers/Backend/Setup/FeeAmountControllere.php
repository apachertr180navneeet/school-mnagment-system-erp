<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeeCategory;
use App\Models\StudentClass;
use App\Models\FeeCategoryAmount;
use Illuminate\Support\Facades\Log;

class FeeAmountControllere extends Controller
{

    /**
     * 🔹 View fee amounts (grouped by fee category)
     */
    public function ViewFeeAmount(){
        try {

            $data['allData'] = FeeCategoryAmount::select('fee_category_id')
                                ->groupBy('fee_category_id')
                                ->get();

            return view('backend.setup.fee_amount.view_fee_amount', $data);

        } catch (\Exception $e) {

            Log::error('ViewFeeAmount Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load data!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add fee amount page
     */
    public function AddFeeAmount(){
        try {

            $data['fee_categories'] = FeeCategory::all();
            $data['classes'] = StudentClass::all();

            return view('backend.setup.fee_amount.add_fee_amount', $data);

        } catch (\Exception $e) {

            Log::error('AddFeeAmount Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store fee amounts
     */
    public function StoreFeeAmount(Request $request){
        try {

            if (!empty($request->class_id)) {

                foreach ($request->class_id as $index => $class_id) {

                    $fee_amount = new FeeCategoryAmount();
                    $fee_amount->fee_category_id = $request->fee_category_id;
                    $fee_amount->class_id = $class_id;
                    $fee_amount->amount = $request->amount[$index];
                    $fee_amount->save();
                }
            }

            return redirect()->route('fee.amount.view')->with([
                'message' => 'Fee Amount Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('StoreFeeAmount Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Insert failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Edit fee amount
     */
    public function EditFeeAmount($fee_category_id){
        try {

            $data['editData'] = FeeCategoryAmount::where('fee_category_id', $fee_category_id)
                                ->orderBy('class_id', 'asc')
                                ->get();

            $data['fee_categories'] = FeeCategory::all();
            $data['classes'] = StudentClass::all();

            return view('backend.setup.fee_amount.edit_fee_amount', $data);

        } catch (\Exception $e) {

            Log::error('EditFeeAmount Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load data!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update fee amounts
     */
    public function UpdateFeeAmount(Request $request, $fee_category_id){
        try {

            if (empty($request->class_id)) {

                return redirect()->route('fee.amount.edit', $fee_category_id)->with([
                    'message' => 'Please select at least one class!',
                    'alert-type' => 'error'
                ]);
            }

            // Delete old records
            FeeCategoryAmount::where('fee_category_id', $fee_category_id)->delete();

            // Insert new records
            foreach ($request->class_id as $index => $class_id) {

                $fee_amount = new FeeCategoryAmount();
                $fee_amount->fee_category_id = $request->fee_category_id;
                $fee_amount->class_id = $class_id;
                $fee_amount->amount = $request->amount[$index];
                $fee_amount->save();
            }

            return redirect()->route('fee.amount.view')->with([
                'message' => 'Fee Amount Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('UpdateFeeAmount Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 View fee amount details
     */
    public function DetailsFeeAmount($fee_category_id){
        try {

            $data['detailsData'] = FeeCategoryAmount::where('fee_category_id', $fee_category_id)
                                    ->orderBy('class_id', 'asc')
                                    ->get();

            return view('backend.setup.fee_amount.details_fee_amount', $data);

        } catch (\Exception $e) {

            Log::error('DetailsFeeAmount Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load details!',
                'alert-type' => 'error'
            ]);
        }
    }

}
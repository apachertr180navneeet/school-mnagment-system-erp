<?php

namespace App\Http\Controllers\Backend\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountOtherCost;
use Illuminate\Support\Facades\Log;

class OtherCostController extends Controller
{

    /**
     * 🔹 View all other costs
     */
    public function OtherCostView(){
        try {

            $data['allData'] = AccountOtherCost::orderBy('id', 'desc')->get();

            return view('backend.account.other_cost.other_cost_view', $data);

        } catch (\Exception $e) {

            Log::error('OtherCostView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load data!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add cost form
     */
    public function OtherCostAdd(){
        try {

            return view('backend.account.other_cost.other_cost_add');

        } catch (\Exception $e) {

            Log::error('OtherCostAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Unable to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store new cost
     */
    public function OtherCostStore(Request $request){
        try {

            // Validate input
            $request->validate([
                'date'   => 'required|date',
                'amount' => 'required|numeric',
            ]);

            $cost = new AccountOtherCost();
            $cost->date = date('Y-m-d', strtotime($request->date));
            $cost->amount = $request->amount;

            // Handle image upload
            if ($request->file('image')) {

                $file = $request->file('image');
                $filename = date('YmdHi').'_'.$file->getClientOriginalName();

                $file->move(public_path('upload/cost_images'), $filename);
                $cost->image = $filename;
            }

            $cost->description = $request->description;
            $cost->save();

            return redirect()->route('other.cost.view')->with([
                'message' => 'Other Cost Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('OtherCostStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Cost creation failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show edit cost form
     */
    public function OtherCostEdit($id){
        try {

            $data['editData'] = AccountOtherCost::findOrFail($id);

            return view('backend.account.other_cost.other_cost_edit', $data);

        } catch (\Exception $e) {

            Log::error('OtherCostEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Cost not found!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update cost
     */
    public function OtherCostUpdate(Request $request, $id){
        try {

            // Validate input
            $request->validate([
                'date'   => 'required|date',
                'amount' => 'required|numeric',
            ]);

            $cost = AccountOtherCost::findOrFail($id);

            $cost->date = date('Y-m-d', strtotime($request->date));
            $cost->amount = $request->amount;

            // Handle image update
            if ($request->file('image')) {

                // Delete old image if exists
                if (!empty($cost->image) && file_exists(public_path('upload/cost_images/'.$cost->image))) {
                    unlink(public_path('upload/cost_images/'.$cost->image));
                }

                $file = $request->file('image');
                $filename = date('YmdHi').'_'.$file->getClientOriginalName();

                $file->move(public_path('upload/cost_images'), $filename);
                $cost->image = $filename;
            }

            $cost->description = $request->description;
            $cost->save();

            return redirect()->route('other.cost.view')->with([
                'message' => 'Other Cost Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('OtherCostUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Cost update failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
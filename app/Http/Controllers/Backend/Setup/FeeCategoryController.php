<?php

namespace App\Http\Controllers\Backend\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeeCategory;
use Illuminate\Support\Facades\Log;

class FeeCategoryController extends Controller
{

    /**
     * 🔹 View all fee categories
     */
    public function ViewFeeCat(){
        try {

            $data['allData'] = FeeCategory::all();

            return view('backend.setup.fee_category.view_fee_cat', $data);

        } catch (\Exception $e) {

            Log::error('ViewFeeCat Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load fee categories!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add fee category page
     */
    public function FeeCatAdd(){
        try {

            return view('backend.setup.fee_category.add_fee_cat');

        } catch (\Exception $e) {

            Log::error('FeeCatAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store fee category
     */
    public function FeeCatStore(Request $request){
        try {

            // Validation
            $request->validate([
                'name' => 'required|unique:fee_categories,name',
            ]);

            // Save
            $data = new FeeCategory();
            $data->name = $request->name;
            $data->save();

            return redirect()->route('fee.category.view')->with([
                'message' => 'Fee Category Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('FeeCatStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Insert failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Edit fee category
     */
    public function FeeCatEdit($id){
        try {

            $editData = FeeCategory::findOrFail($id);

            return view('backend.setup.fee_category.edit_fee_cat', compact('editData'));

        } catch (\Exception $e) {

            Log::error('FeeCatEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Fee category not found!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update fee category
     */
    public function FeeCategoryUpdate(Request $request, $id){
        try {

            $data = FeeCategory::findOrFail($id);

            // Validation
            $request->validate([
                'name' => 'required|unique:fee_categories,name,'.$data->id
            ]);

            // Update
            $data->name = $request->name;
            $data->save();

            return redirect()->route('fee.category.view')->with([
                'message' => 'Fee Category Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('FeeCategoryUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Delete fee category
     */
    public function FeeCategoryDelete($id){
        try {

            $feeCategory = FeeCategory::findOrFail($id);
            $feeCategory->delete();

            return redirect()->route('fee.category.view')->with([
                'message' => 'Fee Category Deleted Successfully',
                'alert-type' => 'info'
            ]);

        } catch (\Exception $e) {

            Log::error('FeeCategoryDelete Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Delete failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
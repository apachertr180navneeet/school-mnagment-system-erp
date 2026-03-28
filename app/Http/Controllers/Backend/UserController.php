<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    /**
     * 🔹 Display all admin users
     */
    public function UserView(){
        try {

            // Fetch all admin users
            $data['allData'] = User::where('usertype', 'Admin')->get();

            return view('backend.user.view_user', $data);

        } catch (\Exception $e) {

            Log::error('UserView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Failed to load users!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show add user form
     */
    public function UserAdd(){
        try {
            return view('backend.user.add_user');

        } catch (\Exception $e) {

            Log::error('UserAdd Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Unable to load form!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Store new user
     */
    public function UserStore(Request $request){
        try {

            // Validate input
            $request->validate([
                'email' => 'required|unique:users,email',
                'name'  => 'required|string|max:255',
                'role'  => 'required'
            ]);

            // Generate random code/password
            $code = rand(1000, 9999);

            // Create new user
            $data = new User();
            $data->usertype = 'Admin';
            $data->role     = $request->role;
            $data->name     = $request->name;
            $data->email    = $request->email;
            $data->password = bcrypt($code);
            $data->code     = $code;
            $data->save();

            return redirect()->route('user.view')->with([
                'message' => 'User Inserted Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('UserStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'User creation failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show edit user form
     */
    public function UserEdit($id){
        try {

            // Find user by ID
            $editData = User::findOrFail($id);

            return view('backend.user.edit_user', compact('editData'));

        } catch (\Exception $e) {

            Log::error('UserEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'User not found!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update user data
     */
    public function UserUpdate(Request $request, $id){
        try {

            // Validate input
            $request->validate([
                'email' => 'required|email|unique:users,email,'.$id,
                'name'  => 'required|string|max:255',
                'role'  => 'required'
            ]);

            // Find user
            $data = User::findOrFail($id);

            // Update fields
            $data->name  = $request->name;
            $data->email = $request->email;
            $data->role  = $request->role;
            $data->save();

            return redirect()->route('user.view')->with([
                'message' => 'User Updated Successfully',
                'alert-type' => 'info'
            ]);

        } catch (\Exception $e) {

            Log::error('UserUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'User update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Delete user
     */
    public function UserDelete($id){
        try {

            // Find user
            $user = User::findOrFail($id);

            // Delete user
            $user->delete();

            return redirect()->route('user.view')->with([
                'message' => 'User Deleted Successfully',
                'alert-type' => 'info'
            ]);

        } catch (\Exception $e) {

            Log::error('UserDelete Error: '.$e->getMessage());

            return back()->with([
                'message' => 'User delete failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
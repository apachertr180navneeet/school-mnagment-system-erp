<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

    /**
     * 🔹 Display user profile
     */
    public function ProfileView(){
        try {
            $id = Auth::user()->id;
            $user = User::findOrFail($id);

            return view('backend.user.view_profile', compact('user'));

        } catch (\Exception $e) {
            Log::error('ProfileView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Something went wrong!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show edit profile form
     */
    public function ProfileEdit(){
        try {
            $id = Auth::user()->id;
            $editData = User::findOrFail($id);

            return view('backend.user.edit_profile', compact('editData'));

        } catch (\Exception $e) {
            Log::error('ProfileEdit Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Unable to load profile!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update user profile
     */
    public function ProfileStore(Request $request){
        try {

            // Fetch authenticated user
            $data = User::findOrFail(Auth::user()->id);

            // Assign form values
            $data->name    = $request->name;
            $data->email   = $request->email;
            $data->mobile  = $request->mobile;
            $data->address = $request->address;
            $data->gender  = $request->gender;

            // Handle image upload
            if ($request->file('image')) {

                $file = $request->file('image');

                // Delete old image if exists
                if (!empty($data->image) && file_exists(public_path('upload/user_images/'.$data->image))) {
                    unlink(public_path('upload/user_images/'.$data->image));
                }

                // Generate unique filename
                $filename = date('YmdHi') . '_' . $file->getClientOriginalName();

                // Move file to public folder
                $file->move(public_path('upload/user_images'), $filename);

                // Save filename
                $data->image = $filename;
            }

            // Save data
            $data->save();

            return redirect()->route('profile.view')->with([
                'message' => 'User Profile Updated Successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('ProfileStore Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Profile update failed!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Show change password page
     */
    public function PasswordView(){
        try {
            return view('backend.user.edit_password');

        } catch (\Exception $e) {
            Log::error('PasswordView Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Unable to load page!',
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * 🔹 Update user password
     */
    public function PasswordUpdate(Request $request){
        try {

            // Validate request
            $request->validate([
                'oldpassword' => 'required',
                'password' => 'required|confirmed|min:6',
            ]);

            // Check current password
            if (!Hash::check($request->oldpassword, Auth::user()->password)) {
                return back()->with([
                    'message' => 'Current password is incorrect!',
                    'alert-type' => 'error'
                ]);
            }

            // Update password
            $user = User::findOrFail(Auth::id());
            $user->password = Hash::make($request->password);
            $user->save();

            // Logout after password change
            Auth::logout();

            return redirect()->route('login')->with([
                'message' => 'Password updated successfully. Please login again.',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {

            Log::error('PasswordUpdate Error: '.$e->getMessage());

            return back()->with([
                'message' => 'Password update failed!',
                'alert-type' => 'error'
            ]);
        }
    }

}
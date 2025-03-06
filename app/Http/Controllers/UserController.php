<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\District;
use App\Models\Pngo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Display a listing of the users
    public function index()
    {
        $users = User::all();
        $districts = District::all();
        $pngos = Pngo::all();
        return view('dashboard.admin.users', compact('users', 'districts', 'pngos'));
    }

    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'status' => 'required',
            'district_id' => 'required|exists:districts,id', 
            'pngo_id' => 'required|exists:pngos,id',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        // Create a new user instance
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt('defaultpassword'); // Set a default password
        $user->district_id = $request->district_id;
        $user->pngo_id = $request->pngo_id;
        $user->status = $request->status;

        // Attempt to save the user and check success
        if ($user->save()) {
            return response()->json(['code' => 1, 'msg' => 'User Added Successfully', 'redirect' => route('users.index')]); // Ensure route('users.index') points to the correct route for redirection
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function getUserDetails(Request $request)
    {
        $user_id = $request->user_id;
        $userDetails = User::find($user_id);
        return response()->json(['details' => $userDetails]);
    }

    public function updateUserDetails(Request $request)
    {
        // Find the user by ID
        $user_id = $request->uid;
        $user = User::find($user_id);

        // Check if the user exists
        if (!$user) {
            return response()->json(['code' => 0, 'msg' => 'User not found']);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users,name,' . $user_id . ',id',  // Exclude the current user's name from uniqueness check
            'email' => 'required|email|unique:users,email,' . $user->id . ',id',  // Exclude the current user's email from uniqueness check
            'status' => 'required',
            'district_id' => 'required|exists:districts,id',
            'pngo_id' => 'required|exists:pngos,id',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        // Update the user details
        $user->name = $request->name;
        $user->email = $request->email;
        $user->district_id = $request->district_id;
        $user->pngo_id = $request->pngo_id;
        $user->status = $request->status;

        // If a password is provided, hash and set it
        if ($request->has('password') && !empty($request->password)) {
            $user->password = bcrypt($request->password); // Update with the new password if provided
        }

        // Save the updated user
        if ($user->save()) {
            return response()->json(['code' => 1, 'msg' => 'User Updated Successfully', 'redirect' => route('users.index')]);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }


    
}


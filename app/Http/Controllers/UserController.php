<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\District;
use App\Models\Pngo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    // Display a listing of the users
    public function index()
    {
        $users = User::with('roles')->get(); // Load user roles
        $districts = District::all();
        $pngos = Pngo::all();
        $roles = Role::all();
        return view('dashboard.admin.users', compact('users', 'districts', 'pngos', 'roles'));
    }

    // Add a new user and assign roles
    public function addUser(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'status' => 'required',
            'district_id' => 'required|exists:districts,id', 
            'pngo_id' => 'required|exists:pngos,id',
            'role_name' => 'required|array',
            'role_name.*' => 'exists:roles,name'
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make('defaultpassword'); // Default password
        $user->district_id = $request->district_id;
        $user->pngo_id = $request->pngo_id;
        $user->status = $request->status;

        if ($user->save()) {
            $user->syncRoles($request->role_name); // Assign multiple roles

            return response()->json(['code' => 1, 'msg' => 'User Added Successfully', 'redirect' => route('users.index')]);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    // Get user details for editing
    public function getUserDetails(Request $request)
    {
        $user_id = $request->user_id;
        $userDetails = User::with('roles')->find($user_id);

        if ($userDetails) {
            return response()->json(['details' => $userDetails]);
        } else {
            return response()->json(['code' => 0, 'msg' => 'User not found']);
        }
    }

    // Update user details
    public function updateUserDetails(Request $request)
    {
        $user_id = $request->uid;
        $user = User::with('roles')->find($user_id);
        if (!$user) {
            return response()->json(['code' => 0, 'msg' => 'User not found']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users,name,' . $user_id . ',id',
            'email' => 'required|email|unique:users,email,' . $user_id . ',id',
            'status' => 'required',
            'district_id' => 'required|exists:districts,id',
            'pngo_id' => 'required|exists:pngos,id',
            'role_name' => 'required|array',
            'role_name.*' => 'exists:roles,name'
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->district_id = $request->district_id;
        $user->pngo_id = $request->pngo_id;
        $user->status = $request->status;

        if ($request->has('password') && !empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        if ($user->save()) {
            $user->syncRoles($request->role_name); // Update roles

            return response()->json(['code' => 1, 'msg' => 'User Updated Successfully', 'redirect' => route('users.index')]);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    // Delete user
    public function deleteUser(Request $request)
    {
        $user = User::find($request->user_id);
        
        if (!$user) {
            return response()->json(['code' => 0, 'msg' => 'User not found']);
        }

        if ($user->delete()) {
            return response()->json(['code' => 1, 'msg' => 'User Deleted Successfully']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function showUserPermissions($userId)
    {
        $user = User::findOrFail($userId);
        $permissions = $user->getAllPermissionsList(); // ✅ Correct way to call it

        return response()->json($permissions);
    }

    // public function test()
    // {
    //     $user = User::findOrFail(4);
    //     $allPermissions = \Spatie\Permission\Models\Permission::all();
    //     $userPermissions = $user->getAllPermissions(); // Using Spatie's method to get all permissions

    //     dd($userPermissions);
    //     $userPermissionsDetails = $allPermissions->whereIn('name', $userPermissions->pluck('name'));
    //     $groupedPermissions = $userPermissionsDetails->groupBy('category');
    //     return response()->json(['permissions' => $groupedPermissions]);
    // }

    public function test()
    {
        // Retrieve the user by ID
        $user = User::findOrFail(7);
        
        // Get the list of all permissions assigned to this user
        $userPermissions = $user->getAllPermissionsList()['all_permissions'];
        
        // Debug: Show the user permissions (names)
        // dd($userPermissions);

        // Get all permissions from the database (Spatie Permission model)
        $allPermissions = \Spatie\Permission\Models\Permission::all();

        // Debug: Show all permissions (permission model instances)
        // dd($allPermissions);
        
        // Filter the permissions to get only the ones assigned to the user by matching names
        $userPermissionsDetails = $allPermissions->whereIn('name', $userPermissions);

        // Debug: Show the filtered permissions
        // dd($userPermissionsDetails);

        // Group the permissions by 'category'
        $groupedPermissions = $userPermissionsDetails->groupBy('category');

        // Final debug: Show the grouped permissions
        // dd($groupedPermissions);

        // Return the grouped permissions in a JSON response
        return response()->json(['permissions' => $groupedPermissions]);
    }





    
}

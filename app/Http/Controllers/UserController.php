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
use Spatie\Permission\PermissionRegistrar;


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
            // 'district_id' => 'required|exists:districts,id', 
            // 'pngo_id' => 'required|exists:pngos,id',
            'role_name' => 'required|array',
            'role_name.*' => 'exists:roles,name'
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make('12345678'); // Default password
        $user->district_id = $request->district_id;
        $user->pngo_id = $request->pngo_id;
        $user->status = $request->status == 1 ? 2 : 0;

        if ($user->save()) {
            $user->syncRoles($request->role_name); // Assign multiple roles
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

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
            return response()->json([
                'details' => [
                    'id' => $userDetails->id,
                    'name' => $userDetails->name,
                    'email' => $userDetails->email,
                    'district_id' => $userDetails->district_id,
                    'pngo_id' => $userDetails->pngo_id,
                    'status' => $userDetails->status,
                    'role_name' => $userDetails->roles->pluck('name')->toArray(), // for multiple roles
                ]
            ]);
        } else {
            return response()->json([
                'code' => 0,
                'msg' => 'User not found'
            ]);
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
            // 'district_id' => 'required|exists:districts,id',
            // 'pngo_id' => 'required|exists:pngos,id',
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
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

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



    public function viewUserPermissions($userId)
    {
        $user = User::findOrFail($userId);
        $userPermissions = $user->getAllPermissionsList()['all_permissions'];
        $allPermissions = \Spatie\Permission\Models\Permission::all();
        $userPermissionsDetails = $allPermissions->whereIn('name', $userPermissions);
        $groupedPermissions = $userPermissionsDetails->groupBy('category');
        return response()->json(['permissions' => $groupedPermissions]);
    }


    public function editPermissions($id)
    {
        $user = User::findOrFail($id);
        $userPermissions = $user->getAllPermissionsList()['all_permissions'];
        $allPermissions = Permission::all();
        $userPermissions = $allPermissions->whereIn('name', $userPermissions);
        // $userPermissions = $user->permissions;
        $userPermissionsByCategory = $userPermissions->groupBy('category');

        return response()->json([
            'user' => $user,
            'userPermissions' => $userPermissionsByCategory, // Grouped user permissions
            'allPermissions' => $allPermissions // All available permissions
        ]);
    }

    // Function to update user permissions
    public function updatePermissions(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id); // Ensure user exists

            $permissions = $request->input('permissions'); // Array of permission IDs

            // Sync user permissions
            $user->permissions()->sync($permissions);
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating permissions: ' . $e->getMessage()], 500);
        }
    }

    public function myProfile()
    {
        $user = auth()->user();
        return view('dashboard.admin.profile', compact('user'));
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'new_password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&^])[A-Za-z\d@$!%*#?&^]{8,}$/'
                ],
            ], [
                'new_password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
            ]);

            $user = auth()->user();
            $user->password = bcrypt($request->new_password);
            $user->status = 1;
            $user->save();

            return redirect()->back()->with('success', 'Password updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while updating the password.');
        }
    }
}

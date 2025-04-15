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
use App\Services\LogService;
use Illuminate\Support\Facades\Log;



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
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'status' => 'required',
            'role_name' => 'required|array',
            'role_name.*' => 'exists:roles,name'
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        try {
            // Create new user
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make('12345678'); // Default password
            $user->district_id = $request->district_id;
            $user->pngo_id = $request->pngo_id;
            $user->status = $request->status == 1 ? 2 : 0;

            // Save user to the database
            if ($user->save()) {
                // Assign roles to the user
                $user->syncRoles($request->role_name);
                app()[PermissionRegistrar::class]->forgetCachedPermissions();

                // Log the user creation action
                LogService::logAction('Add User', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'roles' => implode(',', $request->role_name),
                    'district_id' => $user->district_id,
                    'pngo_id' => $user->pngo_id,
                ]);

                // Return success response
                return response()->json(['code' => 1, 'msg' => 'User Added Successfully', 'redirect' => route('users.index')]);
            } else {
                // Log the error if the user creation fails
                Log::error('Failed to add user', [
                    'name' => $request->name,
                    'email' => $request->email
                ]);
                
                // Return failure response
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        } catch (\Exception $e) {
            // Log any unexpected errors
            Log::error('Error in user creation: ' . $e->getMessage(), [
                'request_data' => $request->all()
            ]);

            // Return error response
            return response()->json(['code' => 0, 'msg' => 'An error occurred. Please try again later.']);
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


    public function updateUserDetails(Request $request)
    {
        // Retrieve the user by ID with their roles
        $user_id = $request->uid;
        $user = User::with('roles')->find($user_id);

        if (!$user) {
            // Log the failed user retrieval for debugging
            Log::warning("User not found", ['user_id' => $user_id]);
            
            return response()->json(['code' => 0, 'msg' => 'User not found']);
        }

        // Validate incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users,name,' . $user_id . ',id',
            'email' => 'required|email|unique:users,email,' . $user_id . ',id',
            'status' => 'required',
            'role_name' => 'required|array',
            'role_name.*' => 'exists:roles,name'
        ]);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        // Update user details
        $user->name = $request->name;
        $user->email = $request->email;
        $user->district_id = $request->district_id;
        $user->pngo_id = $request->pngo_id;
        $user->status = $request->status;

        // If password is provided, update it
        if ($request->has('password') && !empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        try {
            // Save the user and sync roles
            if ($user->save()) {
                $user->syncRoles($request->role_name);  // Update roles
                app()[PermissionRegistrar::class]->forgetCachedPermissions();

                // Log the successful update
                LogService::logAction('Update User', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'roles' => implode(',', $request->role_name),
                    'district_id' => $user->district_id,
                    'pngo_id' => $user->pngo_id,
                ]);

                return response()->json(['code' => 1, 'msg' => 'User Updated Successfully', 'redirect' => route('users.index')]);
            } else {
                // Log failure if save fails
                Log::error('Failed to update user', ['user_id' => $user_id]);

                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Error in user update: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'user_id' => $user_id
            ]);

            return response()->json(['code' => 0, 'msg' => 'An error occurred. Please try again later.']);
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
            $userId = $user->id;

            $user->password = Hash::make($request->new_password);
            $user->status = 1;
            $user->save();

            // ✅ Log using your pattern
            LogService::logAction('Password Change', [
                'user_id' => $userId,
                'message' => "User ID {$userId} changed their password.",
            ]);

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

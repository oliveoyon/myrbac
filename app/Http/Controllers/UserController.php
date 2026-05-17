<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\District;
use App\Models\Pngo;
use App\Models\UserPngoScope;
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
    private array $districtPngoRequiredRoles = ['Paralegal', 'DPO'];
    private array $multiScopeRequiredRoles = ['M&EO', 'PNGO Focal'];

    private function selectedRolesRequireDistrictPngo(Request $request): bool
    {
        return collect((array) $request->input('role_name', []))
            ->intersect($this->districtPngoRequiredRoles)
            ->isNotEmpty();
    }

    private function selectedRolesRequireMultiScope(Request $request): bool
    {
        return collect((array) $request->input('role_name', []))
            ->intersect($this->multiScopeRequiredRoles)
            ->isNotEmpty();
    }

    private function syncPngoScopes(User $user, array $pngoIds): void
    {
        $pngos = Pngo::whereIn('id', array_filter($pngoIds))
            ->get(['id', 'district_id']);

        $user->pngoScopes()->delete();

        foreach ($pngos as $pngo) {
            UserPngoScope::create([
                'user_id' => $user->id,
                'district_id' => $pngo->district_id,
                'pngo_id' => $pngo->id,
            ]);
        }
    }

    // Display a listing of the users
    public function index(Request $request)
    {
        $filters = $request->only(['name', 'full_name', 'district_id', 'pngo_id', 'role_name']);
        $filterRequested = collect($filters)->filter(function ($value) {
            return $value !== null && $value !== '';
        })->isNotEmpty();

        $usersQuery = User::with(['roles', 'district:id,name', 'pngo:id,name', 'pngoScopes.pngo:id,name', 'pngoScopes.district:id,name'])
            ->latest('id');

        if ($filterRequested) {
            $usersQuery
                ->when($request->filled('name'), function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->name . '%');
                })
                ->when($request->filled('full_name'), function ($query) use ($request) {
                    $query->where('full_name', 'like', '%' . $request->full_name . '%');
                })
                ->when($request->filled('district_id'), function ($query) use ($request) {
                    $query->where('district_id', $request->district_id);
                })
                ->when($request->filled('pngo_id'), function ($query) use ($request) {
                    $query->where('pngo_id', $request->pngo_id);
                })
                ->when($request->filled('role_name'), function ($query) use ($request) {
                    $query->whereHas('roles', function ($roleQuery) use ($request) {
                        $roleQuery->where('name', $request->role_name);
                    });
                });
        } else {
            $usersQuery->whereRaw('1 = 0');
        }

        $users = $usersQuery->paginate(25)->withQueryString();
        $districts = District::orderBy('name')->get();
        $pngos = Pngo::with('district:id,name')->orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('dashboard.admin.users', compact('users', 'districts', 'pngos', 'roles', 'filters', 'filterRequested'));
    }

    // Add a new user and assign roles
    public function addUser(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users,name',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'district_id' => 'nullable|exists:districts,id',
            'pngo_id' => 'nullable|exists:pngos,id',
            'status' => 'required',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&^])[A-Za-z\d@$!%*#?&^]{8,}$/'
            ],
            'role_name' => 'required|array',
            'role_name.*' => 'exists:roles,name',
            'scoped_pngos' => 'nullable|array',
            'scoped_pngos.*' => 'exists:pngos,id',
        ], [
            'password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($this->selectedRolesRequireDistrictPngo($request)) {
                if (! $request->filled('district_id')) {
                    $validator->errors()->add('district_id', 'District is required for Paralegal and DPO users.');
                }

                if (! $request->filled('pngo_id')) {
                    $validator->errors()->add('pngo_id', 'PNGO is required for Paralegal and DPO users.');
                }
            }

            if ($this->selectedRolesRequireMultiScope($request) && empty(array_filter((array) $request->input('scoped_pngos', [])))) {
                $validator->errors()->add('scoped_pngos', 'Please select at least one district-PNGO scope for M&EO and PNGO Focal users.');
            }

            $pngo = Pngo::find($request->pngo_id);

            if ($pngo && ! $request->filled('district_id')) {
                $validator->errors()->add('district_id', 'Please select the district for the selected PNGO.');
            }

            if ($pngo && $request->filled('district_id') && (int) $pngo->district_id !== (int) $request->district_id) {
                $validator->errors()->add('pngo_id', 'The selected PNGO does not belong to the selected district.');
            }
        });

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        try {
            // Create new user
            $user = new User();
            $user->full_name = $request->full_name;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make('12345678'); // Default password
            $useSingleScope = ! $this->selectedRolesRequireMultiScope($request) || $this->selectedRolesRequireDistrictPngo($request);
            $user->district_id = $useSingleScope && $request->filled('district_id') ? $request->district_id : null;
            $user->pngo_id = $useSingleScope && $request->filled('pngo_id') ? $request->pngo_id : null;
            $user->status = $request->status == 1 ? 2 : 0;

            // Save user to the database
            if ($user->save()) {
                // Assign roles to the user
                $user->syncRoles($request->role_name);
                $this->syncPngoScopes($user, $this->selectedRolesRequireMultiScope($request) ? (array) $request->input('scoped_pngos', []) : []);
                app()[PermissionRegistrar::class]->forgetCachedPermissions();

                // Log the user creation action
                LogService::logAction('Add User', [
                    'user_id' => $user->id,
                    'full_name' => $user->full_name,
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
        $userDetails?->load('pngoScopes');

        if ($userDetails) {
            return response()->json([
                'details' => [
                    'id' => $userDetails->id,
                    'full_name' => $userDetails->full_name,
                    'name' => $userDetails->name,
                    'email' => $userDetails->email,
                    'district_id' => $userDetails->district_id,
                    'pngo_id' => $userDetails->pngo_id,
                    'status' => $userDetails->status,
                    'role_name' => $userDetails->roles->pluck('name')->toArray(), // for multiple roles
                    'scoped_pngo_ids' => $userDetails->pngoScopes->pluck('pngo_id')->map(fn ($id) => (string) $id)->toArray(),
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
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user_id . ',id',
            'district_id' => 'nullable|exists:districts,id',
            'pngo_id' => 'nullable|exists:pngos,id',
            'status' => 'required',
            'role_name' => 'required|array',
            'role_name.*' => 'exists:roles,name',
            'scoped_pngos' => 'nullable|array',
            'scoped_pngos.*' => 'exists:pngos,id',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($this->selectedRolesRequireDistrictPngo($request)) {
                if (! $request->filled('district_id')) {
                    $validator->errors()->add('district_id', 'District is required for Paralegal and DPO users.');
                }

                if (! $request->filled('pngo_id')) {
                    $validator->errors()->add('pngo_id', 'PNGO is required for Paralegal and DPO users.');
                }
            }

            if ($this->selectedRolesRequireMultiScope($request) && empty(array_filter((array) $request->input('scoped_pngos', [])))) {
                $validator->errors()->add('scoped_pngos', 'Please select at least one district-PNGO scope for M&EO and PNGO Focal users.');
            }

            $pngo = Pngo::find($request->pngo_id);

            if ($pngo && ! $request->filled('district_id')) {
                $validator->errors()->add('district_id', 'Please select the district for the selected PNGO.');
            }

            if ($pngo && $request->filled('district_id') && (int) $pngo->district_id !== (int) $request->district_id) {
                $validator->errors()->add('pngo_id', 'The selected PNGO does not belong to the selected district.');
            }
        });

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        }

        // Update user details
        $user->full_name = $request->full_name;
        $user->name = $request->name;
        $user->email = $request->email;
        $useSingleScope = ! $this->selectedRolesRequireMultiScope($request) || $this->selectedRolesRequireDistrictPngo($request);
        $user->district_id = $useSingleScope && $request->filled('district_id') ? $request->district_id : null;
        $user->pngo_id = $useSingleScope && $request->filled('pngo_id') ? $request->pngo_id : null;
        $user->status = $request->status;

        // If password is provided, update it
        if ($request->has('password') && !empty($request->password)) {
            if (! auth()->user()->can('Change User Password')) {
                return response()->json([
                    'code' => 0,
                    'error' => ['password' => ['You do not have permission to change user passwords.']],
                ]);
            }

            $user->password = Hash::make($request->password);
            $user->status = 2;
        }

        try {
            // Save the user and sync roles
            if ($user->save()) {
                $user->syncRoles($request->role_name);  // Update roles
                $this->syncPngoScopes($user, $this->selectedRolesRequireMultiScope($request) ? (array) $request->input('scoped_pngos', []) : []);
                app()[PermissionRegistrar::class]->forgetCachedPermissions();

                // Log the successful update
                LogService::logAction('Update User', [
                    'user_id' => $user->id,
                    'full_name' => $user->full_name,
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
        $user = auth()->user()->load([
            'district:id,name',
            'pngo:id,name,district_id',
            'pngoScopes.district:id,name',
            'pngoScopes.pngo:id,name',
            'roles:id,name',
        ]);

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

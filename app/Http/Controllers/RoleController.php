<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
        // Get the search query parameter from the request
        $search = $request->query('search');
        $perPage = $request->query('perPage', 5);
        // Get all roles data
        $roles = Role::when($search, function ($query, $search){
            return $query->where('title', 'like','%'.$search.'%');
        });
        if (Auth::user()->role_id != 1) {
            $roles = $roles->where('company_id', auth()->user()->company_id)->orwhere('title', 'Admin')->orwhere('title', 'User');
        }
        $roles = $roles->orderBy('id', 'asc')
        ->paginate($perPage);
        // Loop through each role
        foreach ($roles as $role) {
            // Get the permission IDs for this role
            $permissionIds = explode(',', $role->permission_id);

            // Fetch the permission data for each ID and add it to the permissions array
            $permissions = array();

            foreach ($permissionIds as $permissionId) {
                $permission = Permission::find($permissionId);
                if ($permission) {

                    $permissions[] = $permission;
                }
            }

            // Add the permissions array to the role data
            $role->permissions = $permissions;
        
    }


    // Return the roles data
        return response()->json(['Roles' => $roles]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        }
    



    public function store(Request $request)
{
    try {
        // Validate the incoming request
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'integer',
            'company_id' => 'sometimes|integer|exists:companies,id', // Only required if role_id == 1
        ]);

        // Retrieve the valid permission IDs from the database
        $permissions = Permission::whereIn('id', $validatedData['permissions'])->pluck('id')->toArray();

        // Check if all requested permissions were found
        $missingPermissions = array_diff($validatedData['permissions'], $permissions);
        if (!empty($missingPermissions)) {
            return response()->json([
                'message' => 'Some requested permissions were not found in the database.',
                'missing_permissions' => $missingPermissions,
            ], 404);
        }

        // Determine the company_id
        if (auth()->user()->role_id == 1) {
            if (!isset($validatedData['company_id'])) {
                return response()->json([
                    'message' => 'Company ID is required for admins.',
                ], 422);
            }
            $company_id = $validatedData['company_id'];
        } else {
            $company_id = auth()->user()->company_id;
        }

        // Ensure the company exists
        if (!Company::where('id', $company_id)->exists()) {
            return response()->json([
                'message' => 'The specified company does not exist.',
            ], 404);
        }

        // Create the Role
        $role = Role::create([
            'title' => $validatedData['title'],
            'permission_id' => implode(',', $permissions),
            'company_id' => $company_id,
        ]);

        return response()->json([
            'message' => 'Role created successfully.',
            'data' => $role,
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while creating the role.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function checkPermissions(Request $request)
    {
        try {
            $user = Auth::user();
            $role = Role::findOrFail($user->role_id);

            if (!$role) {
                return response()->json([
                    'message' => 'Role not found'
                ], 404);
            }
            $permissionIds = explode(',', $role->permission_id);

            $permissions = array();
            foreach ($permissionIds as $permissionId) {
                $permission = Permission::find($permissionId);
                if ($permission) {
                    $permissions[] = $permission->name;
                }
            }
            $requestedPermissions = $request->input('permissions');
            $response = [];
            foreach ($requestedPermissions as $permissionName) {

                $response[$permissionName] = in_array($permissionName, $permissions);
            }
            return response()->json(['permissions' => $response]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    public function fetchPermissionsById(Request $request, $userId)
    {
        try {
            $role = Role::find($userId);
            if (!$role) {
                return response()->json([
                    'message' => 'Role not found'
                ], 404);
            }

            $permissionIds = explode(',', $role->permission_id);

            return response()->json(['permissions' => $permissionIds]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
    public function show($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        }

        // Get the permission IDs for this role
        $permissionIds = explode(',', $role->permission_id);

        // Fetch the permission data for each ID and add it to the permissions array
        $permissions = array();
        foreach ($permissionIds as $permissionId) {
            $permission = Permission::find($permissionId);
            if ($permission) {
                if ($permission->type == "SuperAdmin" || $permission->type == "Admin"  ){
                    $role->type = true;
                }
                $permissions[] = $permission;
            }
        }

        // Add the permissions array to the role data
        $role->permissions = $permissions;

        return response()->json([
            'data' => $role
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
        // Find the Role record to be updated
        $role = Role::find($id);

        // Return an error response if the role is not found
        if (!$role) {
            return response()->json([
                'message' => 'Role not found.'
            ], 404);
        }

        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'sometimes|required|max:255',
            'permissions' => 'sometimes|required|array',
            'permissions.*' => 'integer',
            'company_id' => 'sometimes|integer|exists:companies,id',
        ]);
        if (auth()->user()->role_id == 1) {
            if (!isset($validatedData['company_id'])) {
                return response()->json([
                    'message' => 'Company ID is required for admins.',
                ], 422);
            }
            $company_id = $validatedData['company_id'];
        } else {
            $company_id = auth()->user()->company_id;
        }

        // Ensure the company exists
        if (!Company::where('id', $company_id)->exists()) {
            return response()->json([
                'message' => 'The specified company does not exist.',
            ], 404);
        }
        // Update the title attribute of the Role model, if present in the request data
        if (array_key_exists('title', $validatedData)) {
            $role->title = $validatedData['title'];
        }

        // If the 'permissions' attribute is present in the request data, update the associated permissions
        if (array_key_exists('permissions', $validatedData)) {
            // Retrieve the permissions from the permissions table
            $permissions = Permission::whereIn('id', $validatedData['permissions'])->pluck('id')->toArray();

            // Check if all requested permissions were found
            $missingPermissions = array_diff($validatedData['permissions'], $permissions);
            if (!empty($missingPermissions)) {
                return response()->json([
                    'message' => 'Some requested permissions were not found in the database.',
                    'missing_permissions' => $missingPermissions,
                ], 404);
            }

            // Update the permission_id attribute of the Role model with the new permissions
            $role->permission_id = implode(',', $permissions);
        }
        $role->company_id = $company_id;
        // Save the updated Role model
        $role->save();

        // Return a JSON response with the success message and updated role data
        return response()->json([
            'message' => 'Role updated successfully.',
            'data' => $role,
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the role.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        }

        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully'
        ]);
    }
}

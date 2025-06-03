<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        // Get the search input from the query parameter
        $searchInput = $request->input('search');
        $perPage = $request->query('perPage', 5); // Get the perPage value from the request

         // Query the Permission model with the search input and paginate the results
        $permissions = Permission::where('name', 'LIKE', '%'.$searchInput.'%')
        ->orWhere('category', 'LIKE', '%'.$searchInput.'%')
        ->orWhere('description', 'LIKE', '%'.$searchInput.'%')
        ->orderBy('id', 'asc') // Order by ID in ascending order
        ->paginate($perPage);

        // Group the permissions by category
        $groupedCategory = collect($permissions)->groupBy('category');

        // Check if No Permission records were found
        if ($permissions->isEmpty()) {
            // return a 404 error
            return response()->json(['error' => 'Permissions not found.'], 404);
        }
        return response()->json(['data' => $groupedCategory,]);
    }

    public function roleTypePermissions(Request $request)
    {

        // Fetch the requested type from the request
        $requestedType = $request->input('type'); // You should define the name of the input field

        // Query the Permission model with the requested type and paginate the results
        $permissions = Permission::where('type', $requestedType)->get();

        // Group the permissions by category
//        $groupedCategory = collect($permissions)->groupBy('category');
        // dd('categories',$groupedCategory);
        // Check if any Permission records were found
        if ($permissions->isEmpty()) {
            // return a 404 error
            return response()->json(['error' => 'Permissions not found.'], 404);
        }

        return response()->json(['data' => $permissions]);
    }

    public function rolePermissions(Request $request)
    {

         // Query the Permission model with the search input and paginate the results
         $permissions = Permission::whereNotIn('category', ['company', 'permission'])->get();

        // Group the permissions by category
        $groupedCategory = collect($permissions)->groupBy('category');
        // dd('categories',$groupedCategory);
        // Check if any Permission records were found
        if ($permissions->isEmpty()) {
            // return a 404 error
            return response()->json(['error' => 'Permissions not found.'], 404);
        }

        return response()->json(['data' => $groupedCategory]);
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        // Check if permission name already exists in database
        $permission = Permission::where('name', $name)->first();
        if ($permission) {
            return response()->json(['error' => 'Permission name already exists.'], 409);
        }

        // Create a new Permission record
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'category' => 'required|max:255',
            'type' => 'required'
        ]);
        $permission = Permission::create($validatedData);

        // Return a JSON response
        return response()->json([
            'message' => 'Permission created successfully.',
            'data' => $permission,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => "required|max:255|unique:permissions,name,{$id}",
            'description' => 'nullable',
            'category' => 'required|max:255',
            'type' => 'required'
        ]);

        // Find the permission record by id
        $permission = Permission::find($id);

        // If the permission is not found, return a 404 error
        if (!$permission) {
            return response()->json(['error' => 'Permission not found.'], 404);
        }

        // Update the permission record with the validated data
        $permission->update($validatedData);

        // Return a success response
        return response()->json([
            'message' => 'Permission updated successfully.',
            'data'  => $permission
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find the permission record by id
        $permission = Permission::find($id);

        // If the permission is not found, return a 404 error
        if (!$permission) {
            return response()->json(['error' => 'Permission not found.'], 404);
        }

        // Return the permission record
        return response()->json(['data' => $permission]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the permission record by id
        $permission = Permission::find($id);

        // If the permission is not found, return a 404 error
        if (!$permission) {
            return response()->json(['error' => 'Permission not found.'], 404);
        }

        // Delete the Permission record
        $permission->delete();

        // Return a success response
        return response()->json(['message' => 'Permission deleted successfully.']);
    }
}

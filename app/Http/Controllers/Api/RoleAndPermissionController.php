<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\RoleAndPermission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getPermissionsForRole(Request $request)
    {
        return Permission::whereRelation('roles', 'name', $request->role_name)->get();
    }

    public function getAvailablePermissionsOnRoleEdit(Request $request)
    {
        $role = Role::findOrFail($request->role_id);

        return Permission::whereDoesntHave('roles', function ($query) use ($role) {$query->where('roles.id', $role->id);})->get();
    }

    public function createPermission(Request $request)
    {
        $validated = $request->validate([
            'permission_name' => 'required|string|unique:permissions,name'
        ]);

        Permission::create(['name' => $validated['permission_name']]);

        return response()->json(['message' => 'Permission created successfully']);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);
    
        $role = Role::create(['name' => $validated['role_name']]);
        $role->syncPermissions($validated['permissions']);
    
        return response()->json(['message' => 'Role and Permissions created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RoleAndPermission  $roleAndPermission
     * @return \Illuminate\Http\Response
     */
    public function show(RoleAndPermission $roleAndPermission)
    {
        return $roleAndPermission->id;
        //return Role::with('permissions')->findOrFail($roleAndPermission->id);
    }

    public function editRole(Request $request)
    {
        return Role::with('permissions')->findOrFail($request->role_id);
    }

    public function addNewPermissions(Request $request)
    {
        $validated = $request->validate([
            'permission_name' => 'required|string|unique:permissions,name'
        ]);

        // Create permission
        $permission = Permission::create(['name' => $validated['permission_name']]);

        // Attach the permission to the role
        $role = Role::find($request->role_id);
        $role->givePermissionTo($permission);

        $permissions = Permission::all();
        $superAdminRole = Role::where('name', 'super-admin')->first();
        $superAdminRole->syncPermissions($permissions);
        
        return Role::with('permissions')->findOrFail($request->role_id);
    }

    public function newSuperAdminPermission(Request $request)
    {
        // $validated = $request->validate([
        //     'permission_name' => 'required|string|unique:permissions,name'
        // ]);

        // Create permission
        //$permission = Permission::create(['name' => $validated['permission_name']]);

        $permissions = Permission::all();
        $superAdminRole = Role::where('name', 'super-admin')->first();
        $superAdminRole->syncPermissions($permissions);
        
        $superAdmin = User::findOrFail(1);
        // $superAdmin = User::whereHas('roles', function ($query) {
        //     $query->where('name', 'super-admin'); // Replace 'super-admin' with the actual role name
        // })->get();

        $superAdmin->syncPermissions($permissions);
        
        return Role::with('permissions')->findOrFail($request->role_id);
    }

    public function addPermissionToRole(Request $request)
    {
        // 
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);
        // 
        $role = Role::with('permissions')->find($request->role_id);

        $current_permissions = $role->permissions;

        $role->syncPermissions([$current_permissions, $validated['permissions']]);
        
        return Role::with('permissions')->findOrFail($request->role_id);
    }

    public function removePermissionFromRole(Request $request)
    {
        $role = Role::with('permissions')->find($request->role_id);

        $role->revokePermissionTo($request->permission_name);
        $role->save();
        return Role::with('permissions')->findOrFail($request->role_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RoleAndPermission  $roleAndPermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RoleAndPermission $roleAndPermission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RoleAndPermission  $roleAndPermission
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoleAndPermission $roleAndPermission)
    {
        //
    }
}

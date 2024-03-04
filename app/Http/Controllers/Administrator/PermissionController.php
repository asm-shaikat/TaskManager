<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        $roles = Role::whereNotIn('name', ['administrator'])->get();
        $permission = Permission::first();

        return view('administrator.permission.index', compact('permissions', 'roles', 'permission'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'permissionName' => 'required',
        ]);
        Permission::create([
            'name' => $validate['permissionName'], 
        ]);
        return redirect()->back()->with('success', 'Permission created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        $roles = Role::all();
        return view('administrator.permission.edit', compact('permission','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validate = $request->validate([
            'edit_permission_name' => 'required',
        ]);
        $permission->update([
            'name' => $validate['edit_permission_name'], 
        ]);
        return redirect()->back()->with('success', 'Permission created successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->back()->with('success', 'Permission deleted successfully');
    }

    public function assignRole(Request $request, Permission $permission){
        if($permission->hasRole($request->role)){
            return back()->with('success', 'Role already exists');
        }
        $permission->assignRole($request->role);
        return back()->with('success', 'Role assigned successfully');
    }


    public function removeRole($role, Permission $permission)
    {
        $roleName = is_object($role) ? $role->name : $role;

        if ($permission->hasRole($roleName)) {
            $permission->removeRole($roleName);
            return back()->with('message', 'Role Removed successfully');
        }

        return back()->with('message', 'Permission Denied');
    }

    

}

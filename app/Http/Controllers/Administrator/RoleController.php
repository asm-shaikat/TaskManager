<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('administrator.role.index',compact('roles','permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::whereNotIn('name', ['administrator'])->get();
        $permissions = Permission::all();
        return view('administrator.role.create',compact('roles','permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'roleName' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);
    
        // Create the role
        $role = Role::create([
            'name' => $request->roleName,
            'guard_name' => 'web'
        ]);
    
        foreach ($request->permissions as $permissionId) {
            $permission = Permission::find($permissionId);
            if ($permission) {
                $role->givePermissionTo($permission);
            }
        }
    
        // Redirect back with success message
        return redirect()->route('role.index');
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
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::all();

        return view('administrator.role.update', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Role $role)
    {
        $validate = $request->validate([
            'roleName' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
        ]);

        $role->update([
            'name' => $request->roleName,
        ]);

        $role->syncPermissions(intval($request->permissions));

        foreach ($request->permissions as $permissionId) {
            $permission = Permission::find($permissionId);
            if ($permission) {
                $role->givePermissionTo($permission);
            }
        }
        
        return redirect()->back()->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->back()->with('success', 'Role deleted successfully');
    }
}

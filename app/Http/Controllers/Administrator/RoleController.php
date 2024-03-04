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
        $roles = Role::whereNotIn('name', ['administrator'])->get();
        $permissions = Permission::all();
        return view('administrator.role.index',compact('roles','permissions'));
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
            'postName' => 'required',
        ]);
        Role::create([
            'name' => $validate['postName'], 
        ]);
        return redirect()->back()->with('success', 'Role created successfully');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Role $role)
    {
        $validate = $request->validate([
            'edit_role_name' => 'required',
        ]);
        $role->update([
            'name' => $validate['edit_role_name'], 
        ]);
        return redirect()->back()->with('success', 'Role created successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->back()->with('success', 'Role deleted successfully');
    }

    public function givenPermission(Request $request, Role $role){
        if($role->hasPermissionTo($request->permission)){
            return redirect()->back()->with('message', 'Permission Exists');
        }
        $role->givePermissionTo($request->permission);
        return redirect()->back()->with('message', 'Permission Added');
    }

    public function removePermission(Role $role, Permission $permission){
        if($role->hasPermissionTo($permission)){
            $role->revokePermissionTo($permission);
            return back()->with('message', 'Permission Deleted');
        }
        return back()->with('message', 'Permission Denied');

    }
}

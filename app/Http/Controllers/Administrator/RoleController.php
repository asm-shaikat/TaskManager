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
        $rolePer = Role::with('permissions')->get();
        $permissions = Permission::all();
        // return $rolePer;
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
    ]);

    // Create the role
    $role = Role::create([
        'name' => $request->roleName,
        'guard_name' => 'web'
    ]);

    // Sync permissions with the role
    $role->syncPermissions($request->permissions);

    // Redirect back with success message
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
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
     
    }

    public function givenPermission(Request $request, Role $role){
       
    }

    public function removePermission(Role $role, Permission $permission){
       

    }
}

<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'administrator');
        })->get();
        $authid = auth()->user()->id;
        $taskInfo =  DB::table('tasks')->where('user_id', $authid)->get();
        return view('users.index', compact('users', 'taskInfo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles =  Role::all();
        return view('users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required','array'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        foreach ($request->roles as $roleId) {
            $roles = Role::find($roleId);
            if ($roles) {
                $user->assignRole($roles);
            }
        }
        return redirect('/users');
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
        $user = User::find($id);
        $roles = Role::latest()->get();
        if (!$user) {
            return abort(404);
        }    
        return view('users.update', compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $user = User::find($id);

    if (!$user) {
        return abort(404);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'roles' => ['required','array'],

    ]);
    
    $user->update([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
    ]);

    foreach ($request->roles as $roleId) {
        $roles = Role::find($roleId);
        if ($roles) {
            $user->assignRole($roles);
        }
    }
    return redirect()->route('users.index')->with('success', 'User updated successfully');
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) 
    {
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
}

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
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authid = auth()->user()->id;
        $taskInfo = DB::table('tasks')->where('user_id', $authid)->get();
        $user_roles = Role::all();
    
        // Check if the request expects JSON response (for DataTables)
        if(request()->ajax()) {
            $query = User::query();
    
            // If 'show_deleted' parameter is present, include deleted users
            if(request()->has('show_deleted')) {
                $query->onlyTrashed()->where('is_deleted', '=', 0);
            }
    
            return DataTables::eloquent($query)
                ->addColumn('role', function ($user) {
                    $roles = $user->roles->pluck('name')->implode(', ');
                    return $roles;
                })
                ->addColumn('actions', function ($user) {
                    // Define actions based on whether the user is deleted or not
                    if ($user->deleted_at) {
                        return '
                        <a href="' . route('users.restore',$user->id) . '" class="btn" style="background-color: cyan">
                            <img src="' . asset('assets/images/svg/restore.svg') . '" style="filter: invert(100%);" class="w-4" alt="user-svg">
                        </a>
                        <form action="' . route('users.hardDelete', $user->id) . '" method="POST" style="display: inline;">
                            ' . csrf_field() . '
                            ' . method_field('PUT') . '
                            <button type="submit" name="hard_delete" class="btn delete-btn" style="background-color: red">
                                <img src="' . asset('assets/images/svg/trash-solid.svg') . '"  class="w-4" style="filter: invert(100%);" alt="user-svg">
                            </button>
                        </form>
                        ';
                    } else {
                        return '<a href="' . route('users.edit', $user->id) . '" class="btn" style="background-color: green">
                                <img src="' . asset('assets/images/svg/pencil-solid.svg') . '" style="filter: invert(100%);" class="w-4" alt="user-svg">
                            </a>
                            <form action="'.route('users.destroy', $user->id).'" method="POST" style="display: inline;">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="btn delete-btn" style="background-color: red">
                                    <img src="'.asset('assets/images/svg/trash-solid.svg').'"  class="w-4" style="filter: invert(100%);" alt="user-svg">
                                </button>
                            </form>';
                    }
                })
                ->rawColumns(['actions'])
                ->toJson();
        }
    
        return view('users.index', compact('taskInfo','user_roles'));
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
            'role' => ['required'],
        ]);
    
        if ($request->filled('role')) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->assignRole(intval($request->role));
            return redirect('/users');
        } else {
            return back()->withErrors(['role' => 'Please select a role.'])->withInput();
        }
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
        'role' => ['required'],
    ]);

    $user->update([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
    ]);

    $user->syncRoles(intval($request->role));
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

    public function hardDelete(string $id) {
        try {
            $user = User::withTrashed()->find($id);
            $user->update(['is_deleted' => true]);
            return redirect()->back()->with('success', 'User permanently deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete task. Please try again.');
        }
    }
    
    


    // Task restoring function
    public function restore(string $id)
    {   
        $user = User::withTrashed()->find($id);
        $user->restore(); 
        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    { 
        if (auth()->user()->hasRole('administrator')) {
            $query = Task::query();
        } else {
            $query = Task::where('user_id', auth()->id());
        }  
        $users = User::all();
        $tasks = $query->get();
        return view('dashboard',compact('users','tasks'));
    }

    public function index()
    {

        
    }

    public function home(){
        $users = User::all();
        return view('home',compact('users'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {   
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->forceDelete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
}

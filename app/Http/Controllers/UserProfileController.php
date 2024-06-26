<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function change_password()
    {
        return view('profile.update-password', [
            'user' => Auth::user(),
        ]);
    }

    public function updateNameEmail(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        if ($request->hasFile('avatar')) {
            $fileName = time() . '_' . uniqid() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $imagePath = $request->file('avatar')->storeAs('uploads/avatar', $fileName);
            $user->avatar = $imagePath;
        }
        $user->save();

        $user->update($data);

        return redirect()->back()->with('success', 'Name and email updated successfully!');
    }


    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if the old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'The old password is incorrect.'])->withInput();
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::logout();
        return redirect()->back()->with('success', 'Password updated successfully!');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

use App\Http\Controllers\Administrator\HomeController;
use App\Http\Controllers\Administrator\PermissionController;
use App\Http\Controllers\Administrator\RoleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Permission;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/administrator', function () {
    return view('administrator.index');
})->middleware(['auth', 'role:administrator'])->name('administrator.index');


Route::middleware(['auth', 'role:administrator'])->prefix('administrator')->group(function(){
    Route::resource('/',HomeController::class);
    Route::resource('/role',RoleController::class);
    Route::post('/role/{role}/permissions',[RoleController::class,'givenPermission'])->name('administrator.role.permissions');
    Route::delete('/role/{role}/permissions/{permission}',[RoleController::class,'removePermission'])->name('administrator.role.removePermissions');
    Route::resource('/permission',PermissionController::class);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

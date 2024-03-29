<?php

use App\Http\Controllers\Administrator\CommentController;
use App\Http\Controllers\Administrator\HomeController;
use App\Http\Controllers\Administrator\PermissionController;
use App\Http\Controllers\Administrator\RoleController;
use App\Http\Controllers\Administrator\TaskController;
use App\Http\Controllers\ProfileController;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Models\Permission as ModelsPermission;

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


Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('home',HomeController::class);
});

Route::get('/administrator', function () {
    return view('administrator.index');
})->middleware(['auth', 'role:administrator'])->name('administrator.index');


Route::middleware(['auth', 'role:administrator'])->prefix('administrator')->group(function(){
    Route::resource('/',HomeController::class);
    Route::resource('/role',RoleController::class);
    Route::post('/role/{role}/permissions',[RoleController::class,'givenPermission'])->name('administrator.role.permissions');
    Route::delete('/role/{role}/permissions/{permission}',[RoleController::class,'removePermission'])->name('administrator.role.removePermissions');
    Route::post('/permission/{permission}/role',[PermissionController::class,'assignRole'])->name('administrator.permissions.role');
    Route::delete('/permissions/{permissions}/role/{role}',[PermissionController::class,'removeRole'])->name('administrator.removePermissions.role');
    Route::resource('/permission',PermissionController::class);
});



Route::middleware('auth')->group(function () {
    Route::resource('task',TaskController::class);
    Route::get('/tasks/search', [TaskController::class, 'index'])->name('task.search');
    Route::resource('comments',CommentController::class);
    Route::get('/comments/{taskId}',[CommentController::class,'index'])->name('comments.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

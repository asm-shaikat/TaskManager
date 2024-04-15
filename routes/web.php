<?php

use App\Http\Controllers\Administrator\CommentController;
use App\Http\Controllers\Administrator\HomeController;
use App\Http\Controllers\Administrator\PermissionController;
use App\Http\Controllers\Administrator\RoleController;
use App\Http\Controllers\Administrator\TaskController;
use App\Http\Controllers\Administrator\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserProfileController;
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
    Route::resource('/users',UserController::class);
    Route::get('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::put('/users/{id}/hard-delete', [UserController::class, 'hardDelete'])->name('users.hardDelete');
    Route::get('/', [HomeController::class,'dashboard']);
});

Route::get('/administrator', function () {
    return view('administrator.index');
})->middleware(['auth', 'role:administrator'])->name('administrator.index');


Route::middleware(['auth', 'role:administrator'])->prefix('administrator')->group(function(){
    Route::resource('/role',RoleController::class);
});



Route::middleware('auth')->group(function () {
    Route::resource('task',TaskController::class);
    Route::put('/task/{task}', [TaskController::class, 'updateStatus'])->name('task.updateStatus');
    Route::put('/task/{task}/update-priority', [TaskController::class, 'updatePriority'])->name('task.updatePriority');
    Route::put('/task/{task}/update-category', [TaskController::class, 'updateCategory'])->name('task.updateCategory');
    Route::put('/task/{task}/update-due-date', [TaskController::class, 'updateDueDate'])->name('task.updateDueDate');
    Route::put('/task/{task}/update-user-name', [TaskController::class, 'updateUserName'])->name('task.updateUserName');
    Route::get('/tasks/search', [TaskController::class, 'index'])->name('task.search');
    Route::get('/task/{id}/restore', [TaskController::class, 'restore'])->name('task.restore');
    Route::put('/task/{id}/hard-delete', [TaskController::class, 'hardDelete'])->name('task.hardDelete');
    Route::resource('comments',CommentController::class);
    Route::get('/comments/{taskId}',[CommentController::class,'index'])->name('comments.index');

    Route::get('profile',[UserProfileController::class,'edit'])->name('profile.edit');
    Route::get('change-password',[UserProfileController::class,'change_password'])->name('profile.update_password');
    Route::put('/profile/{user}/update-name-email', [UserProfileController::class,'updateNameEmail'])->name('profile.updateNameEmail');
    Route::put('/profile/{user}/update-password', [UserProfileController::class,'updatePassword'])->name('profile.updatePassword');    
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

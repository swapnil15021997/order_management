
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\OrderController;
// Route::middleware('/api')->group(function () {

Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);
Route::post('profile', [LoginController::class, 'profile']);
Route::post('change-password', [LoginController::class, 'change_password']);





Route::post('user-add-edit', [UserController::class, 'user_add_edit']);
Route::post('user-list', [UserController::class, 'user_list']);
Route::post('user-details', [UserController::class, 'user_details']);
Route::post('user-remove', [UserController::class, 'user_remove']);


Route::post('role-add-edit', [UserController::class, 'role_add_and_edit']);
Route::post('role-details', [UserController::class, 'role_details']);
Route::post('role-list', [UserController::class, 'role_list']);
Route::post('role-remove', [UserController::class, 'role_remove']);

Route::post('permission-list', [UserController::class, 'permission_list']);

Route::post('branch-add-edit', [BranchController::class, 'add_edit_branch']);
Route::post('branch-list', [BranchController::class, 'branch_list']);
Route::post('branch-details', [BranchController::class, 'branch_details']);
Route::post('branch-remove', [BranchController::class, 'branch_remove']);

Route::post('order-add', [OrderController::class, 'order_add']);
Route::post('order-list', [OrderController::class, 'order_list']);
Route::post('order-details', [OrderController::class, 'order_details']);
Route::post('order-remove', [OrderController::class, 'order_remove']);



// User




Route::get('test', function () {
    return response()->json(['message' => 'API is working']);
});


// });



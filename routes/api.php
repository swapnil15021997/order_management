
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
// Route::middleware('/api')->group(function () {

Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);
Route::post('profile', [LoginController::class, 'profile']);
Route::post('change-password', [LoginController::class, 'change_password']);



Route::post('user-add-edit', [UserController::class, 'user_add_edit']);
Route::post('user-list', [UserController::class, 'user_list']);
Route::post('user-details', [UserController::class, 'user_details']);


// User




Route::get('test', function () {
    return response()->json(['message' => 'API is working']);
});


// });



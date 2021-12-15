<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistryManagementController;
use App\Http\Controllers\VerifyController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::post('/registry-management', [RegistryManagementController::class, 'store']);
Route::get('/registry-management', [RegistryManagementController::class, 'index']);
Route::get('/registry-management/{id}', [RegistryManagementController::class, 'show']);
Route::put('/registry-management/{id}', [RegistryManagementController::class, 'update']);
Route::delete('/registry-management/{id}', [RegistryManagementController::class, 'destroy']);

Route::get('/verify/{token}', [VerifyController::class, 'VerifyEmail'])->name('verify');

Route::middleware(['auth:sanctum','isAPIAdmin'])->group(function(){
   Route::get('/checkingAuthenticated', function() {
      return response()->json(['message'=>'You are in', 'status'=>200], 200);
   });
});


Route::middleware('auth:sanctum')->group(function(){
   Route::post('/logout', [AuthController::class, 'logout']);
});

<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CampusController;

  
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
  
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('staffregister', [AuthController::class, 'staffregister']);

     
Route::middleware('auth:sanctum')->group( function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refreshtoken', [AuthController::class, 'refreshToken']);

    Route::post('user/{id}', [UserController::class, 'userDetails']);
    Route::post('user/update/{id}', [UserController::class, 'updateUser']);

    Route::resource('blogs', BlogController::class);

    Route::resource('campus', CampusController::class);
    Route::post('campus/update/{id}', [CampusController::class, 'updateCampus']);
    
});

Route::get('unauthorized', function () {
    return response()->json(['error' => 'Unauthorized.'], 401);
})->name('unauthorized');

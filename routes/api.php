<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CodeCheckController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PendingRequestController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\BeneficiaryController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::group([
//     'middleware' => 'api',
// ], function ($router) { /////

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/userProfile', [AuthController::class, 'userProfile']);
    Route::post('/forgotPassword',[ForgotPasswordController::class,'forgotPassword']);
    Route::post('/codeCheck',[CodeCheckController::class,'codeCheck']);
    Route::post('/resetPassword',[ResetPasswordController::class,'resetPassword']);


         ########################     api Staff ########################


    Route::get('/showallstaff', [StaffController::class, 'showAllStaff']);
    Route::post('/createstaff', [StaffController::class, 'createStaff']);
    Route::get('/showstaff/{id}', [StaffController::class, 'showStaff']);
    Route::post('/updatestaff/{id}', [StaffController::class, 'updateStaff']);
    Route::post('/destroystaff/{id}', [StaffController::class, 'destroyStaff']);
    Route::post('/loginstaff', [StaffController::class, 'loginStaff']);


       #################   api beneficiary   ####################


    Route::post('/addbeneficiary', [BeneficiaryController::class, 'addBeneficiary']);




      ################ api pendingRequests #######################

    Route::post('/approverequest/{id}', [PendingRequestController::class, 'approveRequest']);


// });

//

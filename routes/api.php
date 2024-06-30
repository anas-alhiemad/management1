<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CodeCheckController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PendingRequestController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\DocumentsController;


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

         ########################     api Type ########################
    Route::apiResource('types', TypeController::class);

             ########################     api Category ########################

    Route::post('categories/{category}/accept', [CategoryController::class, 'acceptRequest']);
    Route::post('categories/{category}/reject', [CategoryController::class, 'rejectRequest']);

    Route::get('categories/available', [CategoryController::class, 'indexAvailable']);
    Route::get('categories/unavailable', [CategoryController::class, 'indexUnAvailable']);

    Route::apiResource('categories', CategoryController::class);

       #################   api beneficiary   ####################


    Route::post('/addbeneficiary', [BeneficiaryController::class, 'addBeneficiary']);
    Route::post('/updatebeneficiary/{id}', [BeneficiaryController::class, 'updateBeneficiary']);
    Route::get('/getallbeneficiary', [BeneficiaryController::class, 'getAllBeneficiary']);
    Route::get('/getbeneficiary/{id}', [BeneficiaryController::class, 'getBeneficiary']);
    Route::get('/searchbeneficiary/{search}', [BeneficiaryController::class, 'searchBeneficiary']);
    Route::post('/deletebeneficiary/{id}', [BeneficiaryController::class, 'deleteBeneficiary']);
                     ### 
    Route::post('/adddocuments/{id}', [DocumentsController::class, 'addDocuments']);
    Route::get('/showdocuments/{id}', [DocumentsController::class, 'showDocuments']);
    Route::post('/updatedocuments/{id}', [DocumentsController::class, 'updateDocuments']);
    Route::post('/destroydocuments/{id}', [DocumentsController::class, 'destroyDocuments']);




      ################ api pendingRequests #######################

    Route::get('/showallrequest', [PendingRequestController::class, 'showAllRequest']);
    Route::post('/approverequest/{id}', [PendingRequestController::class, 'approveRequest']);
    Route::post('/rejectrequest/{id}', [PendingRequestController::class, 'rejectRequest']);
    Route::post('/updaterequest/{id}', [PendingRequestController::class, 'updateRequest']);
    Route::post('/deleterequest/{id}', [PendingRequestController::class, 'deleteRequest']);


// });

//

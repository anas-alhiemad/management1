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

use App\Http\Controllers\ItemController;

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

     #################   api items   ####################

     Route::post('/items/advancedSearch', [ItemController::class, 'advancedSearch']);
     Route::resource('items', ItemController::class);
 //    Route::get('items', [ItemController::class, 'index']);
// Route::get('items/{item}', [ItemController::class, 'show']);
// Route::post('items', [ItemController::class, 'store']);
// Route::put('items/{item}', [ItemController::class, 'update']);
// Route::delete('items/{item}', [ItemController::class, 'destroy']);

// Route::get('items/type/{typeId}', [ItemController::class, 'filterByType']);
// Route::get('items/category/{categoryId}', [ItemController::class, 'filterByCategory']);
// Route::get('items/status/{status}', [ItemController::class, 'filterByStatus']);

//Route::get('items/{item}/history', [ItemController::class, 'history']);

//Route::get('items/export/excel', [ItemController::class, 'exportToExcel']);
//Route::post('items/import/excel', [ItemController::class, 'importFromExcel']);
//Route::get('items/search', [ItemController::class, 'search']);

       #################   api beneficiary   ####################


    Route::post('/addbeneficiary', [BeneficiaryController::class, 'addBeneficiary']);




      ################ api pendingRequests #######################

    Route::get('/showallrequest', [PendingRequestController::class, 'showAllRequest']);
    Route::post('/approverequest/{id}', [PendingRequestController::class, 'approveRequest']);
    Route::post('/rejectrequest/{id}', [PendingRequestController::class, 'rejectRequest']);
    Route::post('/updaterequest/{id}', [PendingRequestController::class, 'updateRequest']);
    Route::post('/deleterequest/{id}', [PendingRequestController::class, 'deleteRequest']);


// });

//

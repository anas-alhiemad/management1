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
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TrainerController;

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
    Route::get('/searchstaff/{search}', [StaffController::class, 'searchStaff']);



         ########################     api Type ########################
    Route::apiResource('types', TypeController::class);

             ########################     api Category ########################
    Route::middleware(['jwt.auth', 'warehouseguard'])->group(function () {
    Route::post('categories/{category}/accept', [CategoryController::class, 'acceptRequest']);
    Route::post('categories/{category}/reject', [CategoryController::class, 'rejectRequest']);

    Route::get('categories/available', [CategoryController::class, 'indexAvailable']);
    Route::get('categories/unavailable', [CategoryController::class, 'indexUnAvailable']);

    Route::apiResource('categories', CategoryController::class);

    });

    Route::get('categories/available', [CategoryController::class, 'indexAvailable']);
    Route::get('categories/unavailable', [CategoryController::class, 'indexUnAvailable']);

    Route::apiResource('categories', CategoryController::class);

     #################   api items   ####################
     Route::post('items/cunsumeItem/{id}', [ItemController::class, 'cunsumeItem']);
     Route::post('items/export/excel', [ItemController::class, 'exportToExcel']);

     Route::post('items/import/excel', [ItemController::class, 'importFromExcel']);
     Route::post('/items/advancedSearch', [ItemController::class, 'advancedSearch']);
     Route::resource('items', ItemController::class);


 //Route::get('items', [ItemController::class, 'index']);
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
                    ###
    Route::post('/beneficiarywithcourse', [BeneficiaryController::class, 'beneficiaryWithCourse']);
    Route::get('/showbeneficiarywithcourse/{id}', [BeneficiaryController::class, 'ShowBeneficiaryWithCourse']);
    Route::post('/deletebeneficiarywithcourse', [BeneficiaryController::class, 'deleteBeneficiaryWithCourse']);
    Route::post('/trackingbeneficiary', [BeneficiaryController::class, 'trackingBeneficiary']);
                    ###
    Route::get('/ratecompletedbeneficiary', [BeneficiaryController::class, 'RateCompletedBeneficiary']);
    Route::get('/rateproceesingbeneficiary', [BeneficiaryController::class, 'RateProceesingBeneficiary']);
    Route::get('/getaverageage', [BeneficiaryController::class, 'getAverageAge']);
                    ###
    Route::post('beneficiaryexportexcel', [BeneficiaryController::class, 'beneficiaryExportExcel']);
    Route::post('beneficiaryimportexcel', [BeneficiaryController::class, 'beneficiaryImportExcel']);



    Route::get('/showallrequestbeneficiary', [PendingRequestController::class, 'showAllRequestBeneficiary']);
    Route::get('/showallrequesttrainer', [PendingRequestController::class, 'showAllRequestTrainer']);
    Route::get('/showAllRequestItems', [PendingRequestController::class, 'showAllRequestItems']);
    Route::get('/showAllRequestcategory', [PendingRequestController::class, 'showAllRequestCategory']);
    Route::get('/showallrequestCourses', [PendingRequestController::class, 'showAllRequestCourses']);
    Route::post('/approverequest/{id}', [PendingRequestController::class, 'approveRequest']);
    Route::post('/rejectrequest/{id}', [PendingRequestController::class, 'rejectRequest']);
    Route::post('/updaterequest/{id}', [PendingRequestController::class, 'updateRequest']);
    Route::post('/deleterequest/{id}', [PendingRequestController::class, 'deleteRequest']);


      ################ api courses #######################

    Route::post('/addcourse', [CourseController::class, 'addCourse']);
    Route::get('/showallcourses', [CourseController::class, 'showAllCourses']);
    Route::post('/showcourse/{id}', [CourseController::class, 'showCourse']);
    Route::post('/updatecourse/{id}', [CourseController::class, 'updateCourse']);
    Route::post('/destroycourse/{id}', [CourseController::class, 'destroyCourse']);
    Route::post('/updatestatus/{id}', [CourseController::class, 'updateStatus']);
    Route::get('/searchcourse/{search}', [CourseController::class, 'searchCourse']); //1
    Route::get('/ratecompletedcourses', [CourseController::class, 'RateCompletedCourses']);
            ###
    Route::get('/showbeneficiaryrecordcourse/{id}', [CourseController::class, 'ShowBeneficiaryWithCourse']);
    Route::get('/showtrainerrecordcourse/{id}', [CourseController::class, 'ShowTrainerWithCourse']);

      ################ api Trainer #######################

    Route::post('/addtrainer', [TrainerController::class, 'addTrainer']);
    Route::get('/showalltrainer', [TrainerController::class, 'showAllTrainer']);
    Route::post('/showtrainer/{id}', [TrainerController::class, 'showTrainer']);
    Route::post('/updateTrainer/{id}', [TrainerController::class, 'updateTrainer']);
    Route::post('/destroytrainer/{id}', [TrainerController::class, 'destroyTrainer']);
    Route::get('/searchtrainer/{search}', [TrainerController::class, 'searchTrainer']);
               ####
    Route::post('/trainerwithcourse', [TrainerController::class, 'TrainerWithCourse']);
    Route::get('/showtrainerwithcourse/{id}', [TrainerController::class, 'ShowTrainerWithCourse']);
    Route::post('/deletetrainerwithcourse', [TrainerController::class, 'deleteTrainerWithCourse']);
    Route::post('/trackingtrainer', [TrainerController::class, 'trackingTrainer']);
             ####
    Route::post('/adddocumentstrainer/{id}', [DocumentsController::class, 'addDocumentsTrainer']);
    Route::get('/showdocumentstrainer/{id}', [DocumentsController::class, 'showDocumentsTrainer']);
    Route::post('/updatedocumentstrainer/{id}', [DocumentsController::class, 'updateDocumentsTrainer']);
    Route::post('/destroydocumentstrainer/{id}', [DocumentsController::class, 'destroyDocumentsTrainer']);

// });


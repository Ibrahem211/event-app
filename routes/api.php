<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BothController;
use App\Http\Controllers\userController;
use App\Models\Event_comming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// *************************************************************************************************

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [BothController::class, 'logoutUser']);

    // acounte

    Route::get('/auth/delete_acounte', [BothController::class, 'delete_acounte']);
    Route::get('/get_User_Profile', [BothController::class, 'getUserProfile']);
    Route::post('/edit_user_profile', [BothController::class, 'EditUserProfile']);

    // user event //

    Route::post('/createUserEvent', [userController::class, 'createUserEvent']);
    Route::post('/updateUserEvent', [userController::class, 'updateUserEvent']);
    Route::post('/bookTicket', [userController::class, 'bookTicket']);
    Route::post('/deleteEventFromCart', [userController::class, 'deleteEventFromCart']);
    Route::post('/deleteEventFromMyEvent', [userController::class, 'deleteEventFromMyEvent']);

    // favorite //

    Route::post('/addFavorite', [userController::class, 'addFavorite']);
    Route::get('/getFavoriteItems', [userController::class, 'getFavoriteItems']);
    Route::post('/deleteFromFavorite', [userController::class, 'deleteFavorite']);

    //coins //
    Route::post('/buycoins', [userController::class, 'buycoins']);
    Route::get('/showUserCoins', [userController::class, 'showUserCoins']);

    // post //
    Route::post('/createPost', [BothController::class, 'createPost']);
    Route::post('/updatePost', [BothController::class, 'updatePost']);
    Route::post('/deletePost', [BothController::class, 'deletePost']);

    // comments posts//
    Route::post('/AddCommentsPost', [BothController::class, 'AddCommentsPost']);
    Route::post('/deleteComment', [BothController::class, 'deleteComment']);
    Route::post('/updateComment', [BothController::class, 'updateComment']);

    // comments last event//
    Route::post('/AddCommentsLast', [BothController::class, 'AddCommentsLast']);
    Route::post('/deleteCommentlast', [BothController::class, 'deleteCommentlast']);
    Route::post('/updateCommentlast', [BothController::class, 'updateCommentlast']);

    // comments recent event//
    Route::post('/AddCommentsRecent', [BothController::class, 'AddCommentsRecent']);
    Route::post('/deleteCommentRecent', [BothController::class, 'deleteCommentRecent']);
    Route::post('/updateCommentRecent', [BothController::class, 'updateCommentRecent']);

    // Rating //
    Route::post('/rating', [BothController::class, 'rating']);
});

// *************************************************************************************************

Route::controller(BothController::class)->group(function () {
    Route::post('/auth/register', 'registerUser');
    Route::post('/login', 'loginUser');
    Route::post('/search', 'search');
    Route::post('/showSelectItem', 'showSelectItem');
    Route::get('/ShowAllPost', 'ShowAllPost');
    Route::post('/getCommentsByPost', 'getCommentsByPost');
    Route::post('/getCommentsByLast', 'getCommentsByLast');
    Route::post('/getCommentsByRecent', 'getCommentsByRecent');

    Route::get('/getAllServices', 'getAllServices');

    //  show servais //

    Route::get('/showSonger', 'showSonger');
    Route::get('/showCar', 'showCar');
    Route::get('/showFood', 'showFood');
    Route::get('/showDecorations', 'showDecorations');
    Route::get('/showPlaces', 'showPlaces');
    Route::get('/showDress_and_makeups', 'showDress_and_makeups');

    // show all serves //
    Route::get('/ShowAllsonger', 'ShowAllsonger');
    Route::get('/ShowAllDecorations', 'ShowAllDecorations');
    Route::get('/ShowAllCars', 'ShowAllCars');
    Route::get('/ShowAllPlaces', 'ShowAllPlaces');
    Route::get('/ShowAllFood', 'ShowAllFood');
    Route::get('/ShowAllDressAndMakeups', 'ShowAllDressAndMakeups');
    // show categories serves //

    Route::post('/getCategoriesByQuery', 'getCategoriesByQuery');
    Route::get('/getAddress' , 'getAddress');
    Route::get('/getEvent' , 'getEvent');
    Route::post('/getEventDetails' , 'getEventDetails');
});

// *************************************************************************************************

Route::controller(userController::class)->group(function () {
    Route::get('/recent', 'recentEvents');
    Route::post('/recentdetails', 'showEventCommingDetails');
    Route::get('/last', 'lastEvents');
    Route::post('/lastdetails', 'ShowLastEventDetails');
    Route::get('/category', 'category');
    Route::post('/verification', 'lastEventVerification');
    Route::post('/chat','getAnswer');
});

// *************************************************************************************************

Route::middleware(['auth:sanctum', 'AdminAuth'])->group(function () {
    // create prudact
    Route::post('/createEvent', [AdminController::class, 'Creat_Event']);
    Route::post('/CreatePlaces', [AdminController::class, 'CreatePlaces']);
    Route::post('/CreateSongers', [AdminController::class, 'CreateSongers']);
    Route::post('/CreateCar', [AdminController::class, 'CreateCar']);
    Route::post('/createDecoration', [AdminController::class, 'createDecoration']);
    Route::post('/createFood', [AdminController::class, 'createFood']);
    Route::post('/createDressAndMakeup', [AdminController::class, 'createDressAndMakeup']);

    // ****************************************************************************************************
    // update prudact
    Route::post('/updateDressAndMakeup', [AdminController::class, 'updateDressAndMakeup']);
    Route::post('/updateFood', [AdminController::class, 'updateFood']);
    Route::post('/updateDecoration', [AdminController::class, 'updateDecoration']);
    Route::post('/updateCar', [AdminController::class, 'updateCar']);
    Route::post('/updateSongers', [AdminController::class, 'updateSongers']);
    Route::post('/updatePlaces', [AdminController::class, 'updatePlaces']);

    // ****************************************************************************************************
    Route::post('/createCategoryPlace', [AdminController::class, 'createCategoryPlace']);
    Route::get('/numberOfSuppliers', [AdminController::class, 'numberOfSuppliers']);
    Route::get('/numberOfUsers', [AdminController::class, 'numberOfUsers']);
    Route::get('/numberOfEvents', [AdminController::class, 'numberOfEvents']);
    Route::get('/userEventsWithTotalPayments', [AdminController::class, 'userEventsWithTotalPayments']);
    Route::post('/changeviewability', [AdminController::class, 'changeviewability']);
    Route::get('/ShowAllUser', [AdminController::class, 'ShowAllUser']);
    Route::post('DeleteUser', [AdminController::class, 'DeleteUser']);
    Route::post('/deleteServes', [AdminController::class, 'deleteServes']);
    Route::post('/addPhotosToLastEvent' , [AdminController::class , 'addPhotosToLastEvent']);
});

// ************************************************************************************************

Route::middleware(['auth:sanctum', 'ifComplete'])->group(function () {
    Route::post('/getmycard', [userController::class, 'getmycard']);
    Route::post('/getmyevent', [userController::class, 'getmyevents']);
});

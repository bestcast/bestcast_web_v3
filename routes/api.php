<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;


use App\Http\Controllers\Movies\BrowseController;
use App\Http\Controllers\Movies\GuestController;
use App\Http\Controllers\PaymentController;

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



// Public routes
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/loginbyqrcode', [AuthController::class, 'loginbyqrcode'])->name('api.loginbyqrcode');
Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('api.send.otp');
Route::post('/login-with-otp', [AuthController::class, 'loginWithOtp'])->name('api.login.otp');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/emailverify', [AuthController::class, 'emailverify'])->name('api.emailverify');
Route::post('/tokenexist', [AuthController::class, 'tokenexist'])->name('api.tokenexist');
Route::get('/mobileapp/{id}', [AuthController::class, 'mobileapp'])->name('api.mobileapp');
Route::get('/getversion', [AuthController::class, 'getversion'])->name('api.getversion');


Route::group(['middleware' => ['guest']], function () {
    Route::get('/guest/menulist', [GuestController::class, 'menulist'])->name('menulist');
    Route::get('/guest/genrelist', [GuestController::class, 'genrelist'])->name('genrelist');
    Route::get('/guest/languagelist', [GuestController::class, 'languagelist'])->name('languagelist');
    Route::get('/guest/movieslist', [GuestController::class, 'movieslist'])->name('movieslist'); 
    Route::get('/guest/appnotifylist/{id}', [GuestController::class, 'appnotifylist'])->name('appnotifylist');
    Route::get('/guest/getusermovie/{movieid}', [GuestController::class, 'getusermovie'])->name('getusermovie');
    Route::get('/guest/bannerlist', [GuestController::class, 'bannerlist'])->name('bannerlist');
    Route::get('/guest/blockslist', [GuestController::class, 'blockslist'])->name('blockslist');
    Route::get('/guest/latestlist', [GuestController::class, 'latestlist'])->name('latestlist');
    Route::get('/generateqrcode', [GuestController::class, 'generateqrcode'])->name('generateqrcode');
});

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user', [UserController::class, 'index']);
    Route::get('/menulist', [BrowseController::class, 'menulist'])->name('menulist');
    Route::get('/genrelist', [BrowseController::class, 'genrelist'])->name('genrelist');
    Route::get('/languagelist', [BrowseController::class, 'languagelist'])->name('languagelist');
    Route::get('/movieslist', [BrowseController::class, 'movieslist'])->name('movieslist'); 
    //Route::post('/tokenexist', [AuthController::class, 'tokenexist'])->name('api.tokenexist');
    //http://domain.com/api/movieslist
    Route::get('/bannerlist', [BrowseController::class, 'bannerlist'])->name('bannerlist');
    Route::get('/blockslist', [BrowseController::class, 'blockslist'])->name('blockslist');
    Route::get('/latestlist', [BrowseController::class, 'latestlist'])->name('latestlist');


    Route::get('/paymentgatewayinfo', [PaymentController::class, 'paymentgatewayinfo'])->name('paymentgatewayinfo');
    Route::get('/subscriptionlist', [PaymentController::class, 'subscriptionlist'])->name('subscriptionlist');
    Route::post('/createsubscription/{id}', [PaymentController::class, 'createsubscription'])->name('createsubscription');
    Route::post('/updatetransaction', [PaymentController::class, 'updatetransaction'])->name('updatetransaction');
    Route::post('/verifypaymentstatus', [PaymentController::class, 'verifypaymentstatus'])->name('verifypaymentstatus');
    Route::post('/cancelmembership', [PaymentController::class, 'cancelmembership'])->name('cancelmembership');

    Route::get('/getqrcode', [UserController::class, 'getqrcode'])->name('getqrcode');
    Route::post('/setqrcode', [UserController::class, 'setqrcode'])->name('setqrcode');
    
    Route::get('/sendappotp', [UserController::class, 'sendappotp'])->name('sendappotp');
    Route::post('/verifyappotp', [UserController::class, 'verifyappotp'])->name('verifyappotp');


    Route::post('/otp-verification', [UserController::class, 'postloginWithOtp'])->name('api.user.login.otp');

    Route::get('/profileiconlist', [UserController::class, 'profileiconlist'])->name('profileiconlist');
    Route::get('/userprofilelist', [UserController::class, 'userprofilelist'])->name('userprofilelist');
    Route::get('/getuserprofile/{id}', [UserController::class, 'getuserprofile'])->name('getuserprofile');
    Route::get('/userprofilepinverify/{id}', [UserController::class, 'userprofilepinverify'])->name('userprofilepinverify');
    Route::post('/userprofileresetpin/{id}', [UserController::class, 'userprofileresetpin'])->name('userprofileresetpin');
    Route::post('/setuserprofile/{id}', [UserController::class, 'setuserprofile'])->name('setuserprofile');
    Route::post('/deleteuserprofile/{id}', [UserController::class, 'deleteuserprofile'])->name('deleteuserprofile');
    Route::get('/userdevicelist', [UserController::class, 'userdevicelist'])->name('userdevicelist');
    Route::post('/setuserdevice', [UserController::class, 'setuserdevice'])->name('setuserdevice');
    Route::get('/usermovieslist', [UserController::class, 'usermovieslist'])->name('usermovieslist');
    Route::get('/getusermovie/{movieid}', [UserController::class, 'getusermovie'])->name('getusermovie');
    Route::get('/getusermovietime/{movieid}', [UserController::class, 'getusermovietime'])->name('getusermovietime');
    Route::post('/setusermovie/{movieid}', [UserController::class, 'setusermovie'])->name('setusermovie');
    Route::get('/appnotifylist/{id}', [UserController::class, 'appnotifylist'])->name('appnotifylist');
    Route::get('/deleteuser', [UserController::class, 'deleteuser'])->name('deleteuser');
});

//Route::get('/data', [YourController::class, 'noAuthRequired']);
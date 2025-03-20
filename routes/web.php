<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();


//Route::post('/login', 'App\Http\Controllers\Api\AuthController@login')->name('login');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/send-otp', 'App\Http\Controllers\Auth\LoginController@sendOtp')->name('send.otp');
Route::get('/login-with-otp', 'App\Http\Controllers\Auth\LoginController@loginWithOtp')->name('login.otp');
Route::get('/verifyemail', 'App\Http\Controllers\Api\AuthController@verifyemail')->name('verifyemail');

Route::get('/accountlogin/{token}', 'App\Http\Controllers\Api\AuthController@accountlogin')->name('accountlogin');

Route::group(['middleware' => ['auth']], function () {
    // Route::get('/accountlogin/{token}', function ($token) {
    //     return redirect('/my-account');
    // });
});
Route::group(['middleware' => ['guest']], function () {
    #Route::get('/accountlogin/{token}', 'App\Http\Controllers\Api\AuthController@accountlogin')->name('accountlogin');
});


#Route::get('/genrelist', [App\Http\Controllers\Movies\BrowseController::class, 'genrelist'])->name('genrelist');
#Route::get('/languagelist', [App\Http\Controllers\Movies\BrowseController::class, 'languagelist'])->name('languagelist');
#Route::get('/menulist', [App\Http\Controllers\Movies\BrowseController::class, 'menulist'])->name('menulist');
#Route::get('/bannerlist', [App\Http\Controllers\Movies\BrowseController::class, 'bannerlist'])->name('bannerlist');
#Route::get('/blockslist', [App\Http\Controllers\Movies\BrowseController::class, 'blockslist'])->name('blockslist');
#Route::get('/movieslist', [App\Http\Controllers\Movies\BrowseController::class, 'movieslist'])->name('movieslist');
#Route::get('/appnotifylist/{id}', [App\Http\Controllers\Api\UserController::class, 'appnotifylist'])->name('appnotifylist');




#Route::post('/logout', 'App\Http\Controllers\Api\AuthController@logout')->name('logout');

// Route::group([
//     'middleware'    => ['guest'],   'namespace' => 'App\Http\Controllers\Api'
// ], function () {
//     Route::post('/login', 'AuthController@login')->name('login');
//     Route::post('/login-with-otp','AuthController@loginWithOtp')->name('loginWithOtp');
//     Route::post('/send-otp','AuthController@sendOtp')->name('sendOtp');
//     Route::post('/register','AuthController@register')->name('register');
// });


// Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
//     Route::post('/logout','AuthController@logout')->name('logout')->middleware('auth:sanctum');
// });


// Route::group([
//     'middleware'    => ['role:user'],   'namespace' => 'App\Http\Controllers\Api'
// ], function () {
//     Route::post('/logout','AuthController@logout')->name('logout');
// });



//Route::get('/',[App\Http\Controllers\HomeController::class,'index'])->name('home');
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Route::group([
//     'namespace'     => 'App\Http\Controllers',
// ], function () {
//     Route::get('/testestest','HomeController@index')->name('testestest.testestest.testestest');
// });


Route::group([
    'middleware'    => ['role:producer'],
    'namespace'     => 'App\Http\Controllers',
], function () {
   Route::get('/account/producer', 'MyaccountController@producer')->name('user.myaccount.producer');
});


Route::group([
    'middleware'    => ['role:admin,subadmin,user,producer'],
    'namespace'     => 'App\Http\Controllers',
], function () {

    #Route::get('/browse', 'Movies\BrowseController@index')->name('browse');

   

   Route::get('/my-account', 'MyaccountController@index')->name('user.myaccount.index');
   Route::get('/account/profile', 'MyaccountController@profile')->name('user.myaccount.profile');
   Route::post('/account/profile', 'MyaccountController@profileSave')->name('user.myaccount.profilesave');
   Route::get('/account/membership', 'MyaccountController@membership')->name('user.myaccount.membership');
   Route::get('/account/devices', 'MyaccountController@devices')->name('user.myaccount.devices');
   Route::get('/account/profiles', 'MyaccountController@profiles')->name('user.myaccount.profiles');
   Route::get('/account/referfriend', 'MyaccountController@referfriend')->name('user.myaccount.referfriend');
   Route::post('/account/cancelmembership', 'MyaccountController@cancelmembership')->name('user.myaccount.cancelmembership');
   Route::post('/account/logoutuser/{id}', 'MyaccountController@logoutuser')->name('user.myaccount.logoutuser');


    #Route::get('/{urlkey}/{model}/{id}', 'Movies\BrowseController@urlkey')->name('movies.genre');
    #Route::get('/{urlkey}', 'Movies\BrowseController@urlkey');

});




// Route::group([
//     'middleware'    => ['role:user,admin,subadmin'],
//     'namespace'     => 'App\Http\Controllers',
// ], function () {

//     //Route::get('/account/subscribe/confirmation/{contactid}','SubscribeController@confirmation')->name('patient.subscribe.confirmation');

// });


// Route::group([
//     'middleware'    => ['role:admin,subadmin'],
//     'namespace'     => 'App\Http\Controllers',
// ], function () {
    
//     //Route::get('/account/subscribe/providers/{contactid}','SubscribeController@providers')->name('patient.subscribe.providers');

// });
 


Route::group([
    'middleware'    => ['role:admin,subadmin'],
    'namespace'     => 'App\Http\Controllers',
], function () {
    
   Route::get('/admin/my-account', 'MyaccountController@index')->name('admin.myaccount.index');
   Route::get('/admin/account/profile', 'MyaccountController@profile')->name('admin.myaccount.profile');
   Route::post('/admin/account/profile', 'MyaccountController@profileSave')->name('admin.myaccount.profilesave');

});




Route::group([
    'middleware'    => ['role:admin,subadmin'],
    'namespace'     => 'App\Http\Controllers\Admin',
], function () {

    Route::get('/backend', 'DashboardController@index')->name('admin.dashboard.index');
    #Route::get('/admin/dashboard', 'DashboardController@index')->name('admin.dashboard.index');
    Route::get('/admin/config', 'ConfigController@index')->name('admin.config.index');
    Route::get('/admin/config/save','ConfigController@index')->name('admin.config.save');
    Route::post('/admin/config/save','ConfigController@save')->name('admin.config.save');
    Route::get('/admin/config/info', 'ConfigController@info')->name('admin.config.info');
    Route::get('/admin/config/indexer', 'ConfigController@indexer')->name('admin.config.indexer');
    Route::post('/admin/config/indexer/sitemap', 'ConfigController@indexerSitemap')->name('admin.config.indexer.sitemap');
    Route::post('/admin/config/indexer/cache', 'ConfigController@indexerCache')->name('admin.config.indexer.cache');
    Route::post('/admin/config/indexer/regenerate/image', 'ConfigController@regenerateImage')->name('admin.config.regenerate.image');

    Route::get('/admin/post', 'PostController@index')->name('admin.post.index');
    Route::get('/admin/post/list', 'PostController@list')->name('admin.post.list');
    Route::get('/admin/post/view/{id}', 'PostController@view')->name('admin.post.view');
    Route::get('/admin/post/create', 'PostController@create')->name('admin.post.create');
    Route::post('/admin/post/createsave', 'PostController@createsave')->name('admin.post.createsave');
    Route::get('/admin/post/edit/{id}', 'PostController@edit')->name('admin.post.edit');
    Route::post('/admin/post/editsave/{id}', 'PostController@editsave')->name('admin.post.editsave');
    Route::get('/admin/post/delete/{id}', 'PostController@delete')->name('admin.post.delete');


    Route::get('/admin/genres', 'GenresController@index')->name('admin.genres.index');
    Route::get('/admin/genres/list', 'GenresController@list')->name('admin.genres.list');
    Route::get('/admin/genres/create', 'GenresController@create')->name('admin.genres.create');
    Route::post('/admin/genres/createsave', 'GenresController@createsave')->name('admin.genres.createsave');
    Route::get('/admin/genres/edit/{id}', 'GenresController@edit')->name('admin.genres.edit');
    Route::post('/admin/genres/editsave/{id}', 'GenresController@editsave')->name('admin.genres.editsave');
    Route::get('/admin/genres/delete/{id}', 'GenresController@delete')->name('admin.genres.delete');
    Route::get('/admin/genres/searchbytitle', 'GenresController@searchbytitle')->name('admin.genres.searchbytitle');
    Route::get('/admin/genres/searchbytitle/{key}', 'GenresController@searchbytitle')->name('admin.genres.searchbytitle.key');


    Route::get('/admin/languages', 'LanguagesController@index')->name('admin.languages.index');
    Route::get('/admin/languages/list', 'LanguagesController@list')->name('admin.languages.list');
    Route::get('/admin/languages/create', 'LanguagesController@create')->name('admin.languages.create');
    Route::post('/admin/languages/createsave', 'LanguagesController@createsave')->name('admin.languages.createsave');
    Route::get('/admin/languages/edit/{id}', 'LanguagesController@edit')->name('admin.languages.edit');
    Route::post('/admin/languages/editsave/{id}', 'LanguagesController@editsave')->name('admin.languages.editsave');
    Route::get('/admin/languages/delete/{id}', 'LanguagesController@delete')->name('admin.languages.delete');
    Route::get('/admin/languages/searchbytitle', 'LanguagesController@searchbytitle')->name('admin.languages.searchbytitle');
    Route::get('/admin/languages/searchbytitle/{key}', 'LanguagesController@searchbytitle')->name('admin.languages.searchbytitle.key');



    Route::get('/admin/movies', 'MoviesController@index')->name('admin.movies.index');
    Route::get('/admin/movies/list', 'MoviesController@list')->name('admin.movies.list');
    Route::get('/admin/movies/create', 'MoviesController@create')->name('admin.movies.create');
    Route::post('/admin/movies/createsave', 'MoviesController@createsave')->name('admin.movies.createsave');
    Route::get('/admin/movies/edit/{id}', 'MoviesController@edit')->name('admin.movies.edit');
    Route::post('/admin/movies/editsave/{id}', 'MoviesController@editsave')->name('admin.movies.editsave');
    Route::get('/admin/movies/delete/{id}', 'MoviesController@delete')->name('admin.movies.delete');
    Route::get('/admin/movies/searchbytitle', 'MoviesController@searchbytitle')->name('admin.movies.searchbytitle');
    Route::get('/admin/movies/searchbytitle/{key}', 'MoviesController@searchbytitle')->name('admin.movies.searchbytitle.key');



    Route::get('/admin/shows', 'ShowsController@index')->name('admin.shows.index');
    Route::get('/admin/shows/list', 'ShowsController@list')->name('admin.shows.list');
    Route::get('/admin/shows/create', 'ShowsController@create')->name('admin.shows.create');
    Route::post('/admin/shows/createsave', 'ShowsController@createsave')->name('admin.shows.createsave');
    Route::get('/admin/shows/edit/{id}', 'ShowsController@edit')->name('admin.shows.edit');
    Route::post('/admin/shows/editsave/{id}', 'ShowsController@editsave')->name('admin.shows.editsave');
    Route::get('/admin/shows/delete/{id}', 'ShowsController@delete')->name('admin.shows.delete');
    Route::get('/admin/shows/searchbytitle', 'ShowsController@searchbytitle')->name('admin.shows.searchbytitle');
    Route::get('/admin/shows/searchbytitle/{key}', 'ShowsController@searchbytitle')->name('admin.shows.searchbytitle.key');



    Route::get('/admin/banner', 'BannerController@index')->name('admin.banner.index');
    Route::get('/admin/banner/list', 'BannerController@list')->name('admin.banner.list');
    Route::get('/admin/banner/create', 'BannerController@create')->name('admin.banner.create');
    Route::post('/admin/banner/createsave', 'BannerController@createsave')->name('admin.banner.createsave');
    Route::get('/admin/banner/edit/{id}', 'BannerController@edit')->name('admin.banner.edit');
    Route::post('/admin/banner/editsave/{id}', 'BannerController@editsave')->name('admin.banner.editsave');
    Route::get('/admin/banner/delete/{id}', 'BannerController@delete')->name('admin.banner.delete');


    Route::get('/admin/appnotify', 'AppnotifyController@index')->name('admin.appnotify.index');
    Route::get('/admin/appnotify/list', 'AppnotifyController@list')->name('admin.appnotify.list');
    Route::get('/admin/appnotify/create', 'AppnotifyController@create')->name('admin.appnotify.create');
    Route::post('/admin/appnotify/createsave', 'AppnotifyController@createsave')->name('admin.appnotify.createsave');
    Route::get('/admin/appnotify/edit/{id}', 'AppnotifyController@edit')->name('admin.appnotify.edit');
    Route::post('/admin/appnotify/editsave/{id}', 'AppnotifyController@editsave')->name('admin.appnotify.editsave');
    Route::get('/admin/appnotify/delete/{id}', 'AppnotifyController@delete')->name('admin.appnotify.delete');


    Route::get('/admin/mobileapp','MobileappController@index')->name('admin.mobileapp.index');
    Route::post('/admin/mobileapp/save/{id}','MobileappController@save')->name('admin.mobileapp.save');

    Route::get('/admin/paymentgateway','PaymentgatewayController@index')->name('admin.paymentgateway.index');
    Route::post('/admin/paymentgateway/save/{id}','PaymentgatewayController@save')->name('admin.paymentgateway.save');


    Route::get('/admin/refer','ReferController@index')->name('admin.refer.index');
    Route::post('/admin/refer/save/{id}','ReferController@save')->name('admin.refer.save');
    Route::get('/admin/transactions','TransactionController@index')->name('admin.transactions.index');


    Route::get('/admin/blocks', 'BlocksController@index')->name('admin.blocks.index');
    Route::get('/admin/blocks/list', 'BlocksController@list')->name('admin.blocks.list');
    Route::get('/admin/blocks/create', 'BlocksController@create')->name('admin.blocks.create');
    Route::post('/admin/blocks/createsave', 'BlocksController@createsave')->name('admin.blocks.createsave');
    Route::get('/admin/blocks/edit/{id}', 'BlocksController@edit')->name('admin.blocks.edit');
    Route::post('/admin/blocks/editsave/{id}', 'BlocksController@editsave')->name('admin.blocks.editsave');
    Route::get('/admin/blocks/delete/{id}', 'BlocksController@delete')->name('admin.blocks.delete');



    Route::get('/admin/profileicon', 'ProfileiconController@index')->name('admin.profileicon.index');
    Route::get('/admin/profileicon/list', 'ProfileiconController@list')->name('admin.profileicon.list');
    Route::get('/admin/profileicon/create', 'ProfileiconController@create')->name('admin.profileicon.create');
    Route::post('/admin/profileicon/createsave', 'ProfileiconController@createsave')->name('admin.profileicon.createsave');
    Route::get('/admin/profileicon/edit/{id}', 'ProfileiconController@edit')->name('admin.profileicon.edit');
    Route::post('/admin/profileicon/editsave/{id}', 'ProfileiconController@editsave')->name('admin.profileicon.editsave');
    Route::get('/admin/profileicon/delete/{id}', 'ProfileiconController@delete')->name('admin.profileicon.delete');




    Route::get('/admin/subscription', 'SubscriptionController@index')->name('admin.subscription.index');
    Route::get('/admin/subscription/list', 'SubscriptionController@list')->name('admin.subscription.list');
    Route::get('/admin/subscription/create', 'SubscriptionController@create')->name('admin.subscription.create');
    Route::post('/admin/subscription/createsave', 'SubscriptionController@createsave')->name('admin.subscription.createsave');
    Route::get('/admin/subscription/edit/{id}', 'SubscriptionController@edit')->name('admin.subscription.edit');
    Route::post('/admin/subscription/editsave/{id}', 'SubscriptionController@editsave')->name('admin.subscription.editsave');
    Route::get('/admin/subscription/delete/{id}', 'SubscriptionController@delete')->name('admin.subscription.delete');



    Route::get('/admin/notification', 'NotificationController@index')->name('admin.notification.index');
    Route::get('/admin/notification/list', 'NotificationController@list')->name('admin.notification.list');
    Route::get('/admin/notification/markread/{id}', 'NotificationController@markread')->name('admin.notification.markread');
    Route::get('/admin/notification/delete/{id}', 'NotificationController@delete')->name('admin.notification.delete');



    Route::get('/admin/post/ajax', 'PostController@ajax')->name('admin.post.ajax');
    Route::get('/admin/post/ajax/{key}/{selectedid}/{uuid}', 'PostController@ajax')->name('admin.post.ajaxkey');

    
    Route::get('/admin/post/ajaxauthor', 'PostController@ajaxauthor')->name('admin.post.ajaxauthor');
    Route::get('/admin/post/ajaxauthor/{key}/{selectedid}/{uuid}', 'PostController@ajaxauthor')->name('admin.post.ajaxauthorkey');

    Route::get('/admin/post/category', 'CategoryController@category')->name('admin.post.category');
    Route::get('/admin/post/category/create', 'CategoryController@categorycreate')->name('admin.post.category.create');
    Route::get('/admin/post/category/list', 'CategoryController@categorylist')->name('admin.post.category.list');

    Route::get('/admin/page/', 'PageController@index')->name('admin.page.index');
    Route::get('/admin/page/create', 'PageController@create')->name('admin.page.create');
    Route::post('/admin/page/createsave', 'PageController@createsave')->name('admin.page.createsave');
    Route::get('/admin/page/list', 'PageController@list')->name('admin.page.list');
    Route::get('/admin/page/searchbytitle', 'PageController@searchbytitle')->name('admin.page.searchbytitle');
    Route::get('/admin/page/searchbytitle/{key}', 'PageController@searchbytitle')->name('admin.page.searchbytitle.key');


    Route::get('/admin/menu', 'MenuController@index')->name('admin.menu.index');
    Route::get('/admin/menu/create', 'MenuController@create')->name('admin.menu.create');
    Route::post('/admin/menu/createsave', 'MenuController@createsave')->name('admin.menu.createsave');
    Route::get('/admin/menu/edit/{id}', 'MenuController@edit')->name('admin.menu.edit');
    Route::post('/admin/menu/editsave/{id}', 'MenuController@editsave')->name('admin.menu.editsave');
    Route::get('/admin/menu/delete/{id}', 'MenuController@delete')->name('admin.menu.delete');


    Route::get('/admin/media', 'MediaController@index')->name('admin.media.index');
    Route::get('/admin/media/list', 'MediaController@list')->name('admin.media.list');
    Route::get('/admin/media/view/{id}','MediaController@view')->name('admin.media.view');
    Route::get('/admin/media/create', 'MediaController@create')->name('admin.media.create');
    Route::post('/admin/media/createsave', 'MediaController@createsave')->name('admin.media.createsave');
    Route::get('/admin/media/edit/{id}', 'MediaController@edit')->name('admin.media.edit');
    Route::post('/admin/media/editsave/{id}', 'MediaController@editsave')->name('admin.media.editsave');
    Route::get('/admin/media/delete/{id}', 'MediaController@delete')->name('admin.media.delete');
    Route::get('/admin/media/popup/list', 'MediaController@popuplist')->name('admin.media.popuplist');




    Route::get('/admin/user', 'UserController@index')->name('admin.user.index');
    Route::get('/admin/user/editor', 'UserController@editor')->name('admin.user.editor');
    Route::get('/admin/user/producer', 'UserController@producer')->name('admin.user.producer');
    Route::get('/admin/user/actor', 'UserController@actor')->name('admin.user.actor');
    Route::get('/admin/user/actress', 'UserController@actress')->name('admin.user.actress');
    Route::get('/admin/user/director', 'UserController@director')->name('admin.user.director');
    Route::get('/admin/user/musicdirector', 'UserController@musicdirector')->name('admin.user.musicdirector');
    Route::get('/admin/user/admin', 'UserController@admin')->name('admin.user.admin');
    Route::get('/admin/user/list/{id}', 'UserController@list')->name('admin.user.list');
    Route::get('/admin/user/view/{id}', 'UserController@view')->name('admin.user.view');
    Route::get('/admin/user/create', 'UserController@create')->name('admin.user.create');
    Route::post('/admin/user/createsave', 'UserController@createsave')->name('admin.user.createsave');
    Route::get('/admin/user/edit/{id}', 'UserController@edit')->name('admin.user.edit');
    Route::post('/admin/user/editsave/{id}', 'UserController@editsave')->name('admin.user.editsave');
    Route::get('/admin/user/delete/{id}', 'UserController@delete')->name('admin.user.delete');
    Route::get('/admin/user/searchcastbyname', 'UserController@searchcastbyname')->name('admin.user.searchcastbyname');
    Route::get('/admin/user/searchcastbyname/{key}', 'UserController@searchcastbyname')->name('admin.user.searchcastbyname.key');



});


Route::group(['middleware' => ['guest'],'namespace'     => 'App\Http\Controllers'], function () {
    Route::get('/', 'Movies\GuestController@index')->name('guestbrowse');
    Route::get('/guest/search', 'Movies\GuestController@search')->name('guest.search');
    Route::get('/guest/{urlkey}/{model}/{id}', 'Movies\GuestController@urlkey')->name('guest.movies.genre');
});

Route::group([
    'middleware'    => ['role:admin,subadmin,user,producer'],
    'namespace'     => 'App\Http\Controllers',
], function () {
    #Route::get('/userprofilelist', 'Api\UserController@userprofilelist')->name('userprofilelist');
    Route::post('/updateuser', 'Api\UserController@updateuser')->name('updateuser');
    Route::get('/buyplan/{id}', 'PaymentController@buyplan')->name('buyplan');
    Route::post('/updatetransaction', 'PaymentController@updatetransaction')->name('updatetransaction');
    Route::get('/payment-status', 'PaymentController@paymentstatus')->name('paymentstatus');
    Route::get('/getuser', [App\Http\Controllers\Api\UserController::class, 'index']); //testing purpose only used, comment this line
    Route::get('/usermovieslist', [App\Http\Controllers\Api\UserController::class, 'usermovieslist'])->name('usermovieslist');//testing purpose only used, comment this line
    Route::get('/getusermovie/{movieid}', [App\Http\Controllers\Api\UserController::class, 'getusermovie'])->name('getusermovie');//testing purpose only used, comment this line
    //Route::get('/bannerlist', [App\Http\Controllers\Movies\BrowseController::class, 'bannerlist'])->name('bannerlist');

    Route::get('/tokenexist', 'App\Http\Controllers\Api\UserController@tokenexist')->name('tokenexist');
    Route::get('/lost-login/{id}', 'App\Http\Controllers\Api\UserController@lostlogin')->name('lostlogin');
    
    Route::get('/otp-verification', 'App\Http\Controllers\Api\UserController@loginWithOtp')->name('otp.verification');


    Route::get('/payment/test', 'PaymentController@test')->name('test');
    Route::get('/menuapicode', 'Movies\BrowseController@menuapicode')->name('menuapicode');
    Route::post('/menuapicode', 'Movies\BrowseController@menuapicode')->name('menuapicode');

    Route::get('/browse', 'Movies\BrowseController@index')->name('browse');
    Route::get('/search', 'Movies\BrowseController@search')->name('search');
    Route::get('/watch/{id}', 'Movies\BrowseController@watch')->name('watch');
    Route::get('/my-list', 'Movies\BrowseController@mylist')->name('mylist');
    Route::get('/setprofiletoken/{id}', 'Movies\BrowseController@setprofiletoken')->name('setprofiletoken');
    Route::get('/{urlkey}/{model}/{id}', 'Movies\BrowseController@urlkey')->name('movies.genre');
    #Route::get('/{urlkey}', 'Movies\BrowseController@urlkey')->name('urlkey');
    #Route::get('/deleteuser', 'App\Http\Controllers\Api\UserController@deleteuser')->name('deleteuser');
});


Route::group([
    'middleware'    => ['role:user'],
    'namespace'     => 'App\Http\Controllers',
], function () {
    Route::post('/deleteuseraccount', 'App\Http\Controllers\Api\UserController@deleteuseraccount')->name('deleteuseraccount');
});

##Route::post('/contact', [App\Http\Controllers\FormController::class, 'contact'])->name('form.contact');
##Route::post('/newsletter', [App\Http\Controllers\FormController::class, 'newsletter'])->name('form.newsletter');

Route::get('/refer/{id}', 'App\Http\Controllers\HomeController@refer')->name('refer');
Route::get('/{urlkey}', 'App\Http\Controllers\HomeController@urlkey')->name('urlkey');



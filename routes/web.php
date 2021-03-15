<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\server\auth\AuthController;
use App\Http\Controllers\server\MainController;
use App\Http\Controllers\server\app\SettingController;
use App\Http\Controllers\server\app\SearchController;
use App\Http\Controllers\server\app\LoginSmsCodeController;
use App\Http\Controllers\server\app\SlideShowController;
use App\Http\Controllers\server\role\RoleController;
use App\Http\Controllers\server\role\PermissionController;
use App\Http\Controllers\server\user\AdminController;
use App\Http\Controllers\server\user\UserController;
use App\Http\Controllers\server\advertise\AdvertiseController;
use App\Http\Controllers\server\business\BusinessController;
use App\Http\Controllers\server\category\CategoryController;
use App\Http\Controllers\server\region\RegionController;

use App\Http\Controllers\client\HomeController;
use App\Http\Controllers\client\auth\AuthenticateController;
use App\Http\Controllers\client\advertise\UserAdvertiseController;
use App\Http\Controllers\client\business\UserBusinessController;

// ARTISAN ROUTES
Route::get('/clear-cache' , function(){
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return 'cache cleard';
});

// ADMIN ROUTES
Route::prefix('/admin-area')->group(function(){
    Route::get('/' , [AuthController::class , 'login'])->name('login');
    Route::post('/login' , [AuthController::class , 'submitLogin'])->name('submitLogin');
    Route::get('/logout' , [AuthController::class , 'logout'])->name('logout');
    
    Route::group(['middleware' => ['checkAuth']], function () {
        
        Route::get('/Panel/{path}' , [MainController::class , 'panelLoader'])->where('path' , '.*');
        Route::get('/Panel' , [MainController::class , 'panelLoader'])->name('PannelLoader');

        Route::get('/getDashboardDetails' , [MainController::class , 'getDashboardDetails'])->name('getDashboardDetails');
        
        Route::prefix('/users')->group(function(){
            Route::get('/list' , [UserController::class , 'userList'])->name('userList');
            Route::post('/create' , [UserController::class , 'userCreate'])->name('userCreate');
            Route::get('/user/{id}' , [UserController::class , 'userData'])->name('userData');
            Route::post('/edit' , [UserController::class , 'userEdit'])->name('userEdit');
            Route::post('/delete' , [UserController::class , 'userDelete'])->name('userDelete');
            Route::post('/changeBlockStatus' , [UserController::class , 'userChangeBlockStatus'])->name('userChangeBlockStatus');
        });

        Route::prefix('/admins')->group(function(){
            Route::get('/list' , [AdminController::class , 'adminList'])->name('adminList');
            Route::post('/create' , [AdminController::class , 'adminCreate'])->name('adminCreate');
            Route::get('/admin/{id}' , [AdminController::class , 'adminData'])->name('adminData');
            Route::post('/edit' , [AdminController::class , 'adminEdit'])->name('adminEdit');
            Route::post('/delete' , [AdminController::class , 'adminDelete'])->name('adminDelete');
            Route::post('/changeBlockStatus' , [AdminController::class , 'adminchangeBlockStatus'])->name('adminchangeBlockStatus');
            Route::get('/profile' , [AdminController::class , 'adminProfile'])->name('adminProfile');
        });

        Route::prefix('/roles')->group(function(){
            Route::get('/list' , [RoleController::class , 'roleList'])->name('roleList');
            Route::post('/create' , [RoleController::class , 'roleCreate'])->name('roleCreate');
            Route::get('/role/{id}' , [RoleController::class , 'roleData'])->name('roleData');
            Route::post('/edit' , [RoleController::class , 'roleEdit'])->name('roleEdit');
            Route::post('/delete' , [RoleController::class , 'roleDelete'])->name('roleDelete');
        });

        Route::prefix('/permissions')->group(function(){
            Route::get('/list' , [PermissionController::class , 'permissionList'])->name('permissionList');
            Route::post('/create' , [PermissionController::class , 'permissionCreate'])->name('permissionCreate');
            Route::get('/permission/{id}' , [PermissionController::class , 'permissionData'])->name('permissionData');
            Route::post('/edit' , [PermissionController::class , 'permissionEdit'])->name('permissionEdit');
            Route::post('/delete' , [PermissionController::class , 'permissionDelete'])->name('permissionDelete');
        });

        // Advertises Routes
        Route::prefix('/advertises')->group(function(){
            Route::get('/list' , [AdvertiseController::class , 'advertiseList'])->name('advertiseList');
            Route::post('/create' , [AdvertiseController::class , 'advertiseCreate'])->name('advertiseCreate');
            Route::get('/advertise/{id}' , [AdvertiseController::class , 'advertiseData'])->name('advertiseData');
            Route::post('/edit' , [AdvertiseController::class , 'advertiseEdit'])->name('advertiseEdit');
            Route::post('/delete' , [AdvertiseController::class , 'advertiseDelete'])->name('advertiseDelete');
            Route::post('/changeStatus' , [AdvertiseController::class , 'advertiseChangeStatus'])->name('advertiseChangeStatus');
            Route::post('/search' , [AdvertiseController::class , 'advertiseSearch'])->name('advertiseSearch');
            Route::get('/regions' , [AdvertiseController::class , 'advertiseRegions'])->name('advertiseRegions');
        });

        // Businesses Routes
        Route::prefix('/businesses')->group(function(){
            Route::get('/list' , [BusinessController::class , 'businessList'])->name('businessList');
            Route::post('/create' , [BusinessController::class , 'businessCreate'])->name('businessCreate');
            Route::get('/business/{id}' , [BusinessController::class , 'businessData'])->name('businessData');
            Route::post('/edit' , [BusinessController::class , 'businessEdit'])->name('businessEdit');
            Route::post('/delete' , [BusinessController::class , 'businessDelete'])->name('businessDelete');
            Route::post('/changeStatus' , [BusinessController::class , 'businessChangeStatus'])->name('businessChangeStatus');
            Route::post('/search' , [BusinessController::class , 'businessSearch'])->name('businessSearch');
            Route::get('/categories' , [BusinessController::class , 'businessCategories'])->name('businessCategories');
        });

        // Categories Routes
        Route::prefix('/categories')->group(function(){
            Route::get('/list' , [CategoryController::class , 'categoryList'])->name('categoryList');
            Route::post('/create' , [CategoryController::class , 'categoryCreate'])->name('categoryCreate');
            Route::get('/category/{id}' , [CategoryController::class , 'categoryData'])->name('categoryData');
            Route::post('/edit' , [CategoryController::class , 'categoryEdit'])->name('categoryEdit');
            Route::post('/delete' , [CategoryController::class , 'categoryDelete'])->name('categoryDelete');
        });

        // Regions Routes
        Route::prefix('/regions')->group(function(){
            Route::get('/list' , [RegionController::class , 'regionList'])->name('regionList');
            Route::post('/create' , [RegionController::class , 'regionCreate'])->name('regionCreate');
            Route::get('/region/{id}' , [RegionController::class , 'regionData'])->name('regionData');
            Route::post('/edit' , [RegionController::class , 'regionEdit'])->name('regionEdit');
            Route::post('/delete' , [RegionController::class , 'regionDelete'])->name('regionDelete');
        });

        Route::prefix('/settings')->group(function(){
            Route::get('/setting' , [SettingController::class , 'settingData'])->name('settingData');
            Route::post('/edit' , [SettingController::class , 'settingEdit'])->name('settingEdit');
        });
        
        Route::prefix('/login_sms_codes')->group(function(){
            Route::get('/list' , [LoginSmsCodeController::class , 'LoginSmsCodeList'])->name('LoginSmsCodeList');
            Route::get('/delete' , [LoginSmsCodeController::class , 'LoginSmsCodeDelete'])->name('LoginSmsCodeDelete');
        });
        
        Route::prefix('/searches')->group(function(){
            Route::get('/list' , [SearchController::class , 'SearchList'])->name('SearchList');
            Route::get('/delete' , [SearchController::class , 'SearchDelete'])->name('SearchDelete');
        });

        Route::prefix('/slideshows')->group(function(){
            Route::get('/list' , [SlideShowController::class , 'slideshowList'])->name('slideshowList');
            Route::post('/create' , [SlideShowController::class , 'slideshowCreate'])->name('slideshowCreate');
            Route::get('/slideshow/{id}' , [SlideShowController::class , 'slideshowData'])->name('slideshowData');
            Route::post('/edit' , [SlideShowController::class , 'slideshowEdit'])->name('slideshowEdit');
            Route::post('/delete' , [SlideShowController::class , 'slideshowDelete'])->name('slideshowDelete');
            Route::post('/changeStatus' , [SlideShowController::class , 'slideshowChangeStatus'])->name('slideshowChangeStatus');
        });
        
        Route::prefix('/notification')->group(function(){
            Route::post('/delete' , [MainController::class , 'notificationDelete'])->name('notificationDelete');
            Route::post('/delete/all' , [MainController::class , 'notificationDeleteAll'])->name('notificationDeleteAll');
        });

    });
});

// SITE ROUTES
Route::get('/' , [HomeController::class , 'index'])->name('index');

Route::post('/sendCode' , [AuthenticateController::class , 'sendCode'])->name('user.sendCode');
Route::post('/verifyCode' , [AuthenticateController::class , 'verifyCode'])->name('user.verifyCode');
Route::post('/register' , [AuthenticateController::class , 'register'])->name('user.register');
Route::post('/getUserData' , [AuthenticateController::class , 'getUserData'])->name('user.getUserData');

Route::get('/getUserAdvertises' , [UserAdvertiseController::class , 'getUserAdvertises'])->name('user.getUserAdvertises');
Route::get('/getUserBusinesses' , [UserBusinessController::class , 'getUserBusinesses'])->name('user.getUserBusinesses');

Route::get('/getSettings' , [HomeController::class , 'getSettings'])->name('user.getSettings');

Route::prefix('/advertises')->group(function(){
    Route::get('/list' , [UserAdvertiseController::class , 'advertiseList'])->name('user.advertiseList');
    Route::post('/create' , [UserAdvertiseController::class , 'advertiseCreate'])->name('user.advertiseCreate');
    Route::get('/advertise/{id}' , [UserAdvertiseController::class , 'advertiseData'])->name('user.advertiseData');
    Route::post('/edit' , [UserAdvertiseController::class , 'advertiseEdit'])->name('user.advertiseEdit');
    Route::post('/delete' , [UserAdvertiseController::class , 'advertiseDelete'])->name('user.advertiseDelete');
    Route::post('/changeStatus' , [UserAdvertiseController::class , 'advertiseChangeStatus'])->name('user.advertiseChangeStatus');
    Route::post('/search' , [UserAdvertiseController::class , 'advertiseSearch'])->name('user.advertiseSearch');
    Route::get('/regions' , [UserAdvertiseController::class , 'advertiseRegions'])->name('user.advertiseRegions');
});

Route::prefix('/businesses')->group(function(){
    Route::get('/list' , [UserBusinessController::class , 'businessList'])->name('user.businessList');
    Route::post('/create' , [UserBusinessController::class , 'businessCreate'])->name('user.businessCreate');
    Route::get('/business/{id}' , [UserBusinessController::class , 'businessData'])->name('user.businessData');
    Route::post('/edit' , [UserBusinessController::class , 'businessEdit'])->name('user.businessEdit');
    Route::post('/search' , [UserBusinessController::class , 'businessSearch'])->name('user.businessSearch');
    Route::post('/vote' , [UserBusinessController::class , 'businessVote'])->name('user.businessVote');
    Route::post('/comment' , [UserBusinessController::class , 'businessComment'])->name('user.businessComment');
    Route::get('/categories' , [UserBusinessController::class , 'businessCategories'])->name('user.businessCategories');
});
Route::get('/{path}' , [HomeController::class , 'index'])->where('path' , '.*');
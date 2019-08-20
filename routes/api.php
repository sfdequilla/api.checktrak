<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/test', 'TestController@index');

Route::post('/login', 'AuthController@login');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');

Route::middleware(['auth:api'])->group(function() {
    Route::post('/logout', 'AuthController@logout');
    Route::get('/auth', 'AuthController@user');

    Route::apiResource('/user', 'UserController');
    Route::post('/user/{user}/access', 'UserController@access');

    Route::apiResource('/group', 'GroupController');
    Route::apiResource('/company', 'CompanyController');
    Route::apiResource('/branch', 'BranchController');
    Route::apiResource('/payee', 'PayeeController');

    Route::prefix('/{company}')->group( function() {
        Route::apiResource('/account', 'AccountController');
    });

    Route::prefix('/tools')->group( function() {
        Route::get('/actions', "ToolController@actions");
        Route::get('/branches', "ToolController@branches");
        Route::get('/modules', "ToolController@modules");
    });
});

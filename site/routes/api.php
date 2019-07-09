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

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/login', 'Auth\LoginController@login');

Route::group([
    'middleware' => [
        'authenticated',
        'jwt.auth'
    ]
], function() {
    Route::post('auth/logout', 'Auth\LoginController@logout');
    Route::post('auth/me', 'Auth\LoginController@me');
    Route::post('auth/refresh', 'Auth\LoginController@refresh');

    /*
     * CRUD - Products
     */
    Route::apiResource('product', 'ProductController');

    Route::fallback(function(\Flugg\Responder\Responder $responder) {
        return $responder->error(404, 'Produto não encontrado')->respond(404);
    })->name('productFallback');
});

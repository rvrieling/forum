<?php


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
Route::post('login', ['uses' => 'AuthController@login']);

Route::post('register', ['uses' => 'AuthController@register']);

Route::group(
    [
        'prefix' => 'profile',
    ],
    function () {
        Route::get('/', ['uses' => 'ProfileController@index']);
        Route::get('/image/{username}', ['uses' => 'ImageController@ProfileImage']);
    }
);

Route::group(
    [
        'prefix'    => 'posts',
    ],
    function () {
        Route::get('/{filter?}', ['uses' => 'PostController@index']);
        Route::get('/image/{id}', ['uses' => 'ImageController@postImage']);
    }
);

Route::group(
    [
    'prefix'     => 'categories',
    ],
    function () {
        Route::get('/', ['uses' => 'CategoryController@index']);
    }
);

Route::group(
    [
        'prefix' => 'user'
    ],
    function () {
        Route::get('image/{id}', ['uses' => 'ImageController@userImage']);
    }
);

Route::get('chat/rooms/image/{name}', ['uses' => 'ImageController@chatImage']);

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
Route::post('logout', ['uses' => 'AuthController@logout']);

Route::group(
    [
    'prefix'     => 'categories',
    ],
    function () {
        Route::post('create', ['uses' => 'CategoryController@create']);
        Route::post('edit', ['uses' => 'CategoryController@edit']);
        Route::post('delete', ['uses' => 'CategoryController@delete']);
    }
);

Route::group(
    [
        'prefix'    => 'posts',
    ],
    function () {
        Route::post('create', ['uses' => 'PostController@create']);
        Route::post('edit', ['uses' => 'PostController@edit']);
        Route::post('delete', ['uses' => 'PostController@delete']);
    }
);

Route::group(
    [
        'prefix' => 'comments',
    ],
    function () {
        Route::get('/', ['uses' => 'CommentController@index']);
        Route::post('create', ['uses' => 'CommentController@create']);
        Route::post('edit', ['uses' => 'CommentController@edit']);
        Route::post('delete', ['uses' => 'CommentController@delete']);
    }
);

Route::group(
    [
        'prefix' => 'profile',
    ],
    function () {
        Route::post('create', ['uses' => 'ProfileController@create']);
        Route::post('edit', ['uses' => 'ProfileController@edit']);
        Route::post('user/edit', ['uses' => 'AuthController@editUser']);
    }
);

Route::group(
    [
        'prefix' => 'address',
    ],
    function () {
        Route::get('/{name}', ['uses' => 'AddressController@index']);
        Route::post('create', ['uses' => 'AddressController@create']);
        Route::post('edit', ['uses' => 'AddressController@edit']);
        Route::post('delete', ['uses' => 'AddressController@delete']);
    }
);

Route::group(
    [
        'prefix' => 'subs'
    ],
    function () {
        Route::get('/', ['uses' => 'SubController@index']);
        Route::post('update', ['uses' => 'SubController@update']);
    }
);

Route::group(
    [
        'prefix' => 'likes'
    ],
    function () {
        Route::post('update', ['uses' => 'LikeController@update']);
    }
);

Route::group(
    [
        'prefix' => 'user'
    ],
    function () {
        Route::get('/', ['uses' => 'UserController@getUser']);
        Route::post('update', ['uses' => 'UserController@update']);
    }
);

Route::group(
    [
        'prefix' => 'chat'
    ],
    function () {
        Route::group(
            [
                'prefix' => 'rooms'
            ],
            function () {
                Route::get('/', ['uses' => 'Chat\RoomController@index']);
                Route::post('create', ['uses' => 'Chat\RoomController@create']);
                Route::post('read', ['uses' => 'Chat\RoomController@read']);
                Route::post('leave', ['uses' => 'Chat\RoomController@leaveRoom']);
                Route::post('add/user', ['uses' => 'Chat\RoomController@addUser']);
                Route::post('admin', ['uses' => 'Chat\RoomController@admin']);
            }
        );
        Route::group(
            [
                'prefix' => 'room'
            ],
            function () {
                Route::get('/{id}', ['uses' => 'Chat\ChatController@index']);
                Route::post('create', ['uses' => 'Chat\ChatController@create']);
            }
        );
    }
);

Route::group(
    [
        'prefix' => 'settings'
    ],
    function () {
        Route::post('sort_by', ['uses' => 'SettingsController@updateSort']);
    }
);

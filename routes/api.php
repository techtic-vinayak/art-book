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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
}); */

Route::post('login', 'UserController@authenticate');
Route::post('sign-up', 'UserController@register');
Route::post('forget-password', 'UserController@forgetPassword');
Route::post('social-media-register', 'UserController@socialMediaRegister');
Route::post('set-password', 'UserController@setPassword');


Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('update-profile', 'UserController@updateProfile');
    Route::post('change-password', 'UserController@changePassword');
    Route::get('user', 'UserController@getAuthenticatedUser');

    Route::post('add-art', 'ArtController@addArt');
    Route::post('edit-art', 'ArtController@editArt');
    Route::get('detail-art/{id}', 'ArtController@detailArt');

    Route::post('delete-video', 'VideoController@deleteVideo');
    Route::post('video-view', 'VideoController@addVideoView');
    Route::post('video-report', 'VideoController@addVideoReport');
    Route::post('add-reply', 'ReplyController@addReply');
    Route::get('reply-list', 'ReplyController@getListReply');
    Route::get('near-by-users', 'UserController@nearByUser');
    Route::post('phone-contacts', 'ContactController@getPhoneContacts');
    Route::resource('contacts', 'ContactController', ['except' => ['edit', 'create', 'update']]);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/edit-friendship', "App\Http\Controllers\Controller@edit_friendship")->name("edit_friendship");
    // CHEAT
    Route::post('/show-messages', "App\Http\Controllers\Controller@show_messages")->name("show_messages");
    Route::post('/insert-message', "App\Http\Controllers\Controller@insert_message")->name("insert_message");
    // LIKE
    Route::post('/like', "App\Http\Controllers\Controller@like")->name("like");
    // POSTS AND REVIEWS
    Route::post('/insert-post', "App\Http\Controllers\Controller@insert_post")->name("insert_post");
    Route::post('/delete_post', "App\Http\Controllers\Controller@delete_post")->name("delete_post");
    // COMMENTS
    Route::post('/insert_comment', "App\Http\Controllers\Controller@insert_comment")->name("insert_comment");
    Route::post('/delete_comment', "App\Http\Controllers\Controller@delete_comment")->name("delete_comment");
    // COMPARTMENTS
    Route::post('/new_info', "App\Http\Controllers\Controller@new_info")->name("new_info");
    Route::post('/get_info_files', "App\Http\Controllers\Controller@get_info_files")->name("get_info_files");
    Route::post('/delete_something', "App\Http\Controllers\Controller@delete_something")->name("delete_something");
    Route::middleware('admin')->group(function () {
        Route::post('/getInfo', "App\Http\Controllers\AdminOperationsController@getInfo")->name("getInfo");
        Route::post('/updateInfo', "App\Http\Controllers\AdminOperationsController@updateInfo")->name("updateInfo");
        Route::post('/deleteInfo', "App\Http\Controllers\AdminOperationsController@deleteInfo")->name("deleteInfo");
        Route::post('/checkIfArrayIsAllowedForDelete', "App\Http\Controllers\AdminOperationsController@checkIfArrayIsAllowedForDelete")->name("checkIfArrayIsAllowedForDelete");
        Route::post('/getRoleOfUser', "App\Http\Controllers\AdminOperationsController@getRoleOfUser")->name("getRoleOfUser");
    });
});
Route::post('/get_posts_reviews', "App\Http\Controllers\Controller@get_posts_reviews")->name("get_posts_reviews");
Route::post('/get_files', "App\Http\Controllers\Controller@get_files")->name("get_files");
Route::post('/get_comments', "App\Http\Controllers\Controller@get_comments")->name("get_comments");
Route::post('/send_feedback', "App\Http\Controllers\mailController@send_feedback")->name("send_feedback");
Route::post('/GetStatistics', "App\Http\Controllers\Statistics@GetStatistics")->name("GetStatistics");

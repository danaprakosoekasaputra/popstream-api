<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DBFetchController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/login', [UserController::class, 'login']);
Route::post('/login2', [UserController::class, 'login2']);
Route::post('/login_by_phone', [UserController::class, 'login_by_phone']);
Route::get('/get_livestreams', [UserController::class, 'get_livestreams']);
Route::post('/broadcast', [UserController::class, 'broadcast']);
Route::post('/get_broadcast', [UserController::class, 'get_broadcast']);
Route::post('/delete_broadcast', [UserController::class, 'delete_broadcast']);
Route::post('/add_livestream_member', [UserController::class, 'add_livestream_member']);
Route::get('/get_banners', [UserController::class, 'get_banners']);
Route::get('/get_games', [UserController::class, 'get_games']);
Route::get('/dbfetcher/fetch_countries', [DBFetchController::class, 'fetch_countries']);
Route::get('/dbfetcher/fetch_regions', [DBFetchController::class, 'fetch_regions']);
Route::get('/get_countries', [UserController::class, 'get_countries']);
Route::get('/get_nearby_countries', [UserController::class, 'get_nearby_countries']);
Route::get('/get_regions', [UserController::class, 'get_regions']);
Route::post('/get_popular_topics', [UserController::class, 'get_popular_topics']);
Route::get('/get_most_viewed_lives', [UserController::class, 'get_most_viewed_lives']);
Route::post('/get_lives_by_topic', [UserController::class, 'get_lives_by_topic']);
Route::get('/get_thai_lives', [UserController::class, 'get_thai_lives']);
Route::get('/get_tieng_viet_lives', [UserController::class, 'get_tieng_viet_lives']);
Route::post('/get_novice_missions', [UserController::class, 'get_novice_missions']);
Route::get('/get_latest_livestreams', [UserController::class, 'get_latest_livestreams']);

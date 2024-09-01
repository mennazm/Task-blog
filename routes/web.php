<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

use App\Jobs\DeleteOldPosts;
use App\Jobs\FetchRandomUser;

Route::get('/test-delete-old-posts', function () {
    dispatch(new DeleteOldPosts());
    return 'DeleteOldPosts job dispatched';
});

Route::get('/test-fetch-random-user', function () {
    dispatch(new FetchRandomUser());
    return 'FetchRandomUser job dispatched';
});

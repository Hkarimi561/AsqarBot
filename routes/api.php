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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('/setWebhook', "TelegramController@setHook");
Route::any('/AAG-SYKASFonUkzgoayUPzj2d0Jx5Mv-SL8/webhook', "TelegramController@webhookHandler");
Route::any('/testhook', "TelegramController@hookTester");

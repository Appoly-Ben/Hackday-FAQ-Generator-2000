<?php

use App\Http\Controllers\OpenAiController;
use App\Http\Controllers\ProfileController;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/ask-question', [OpenAiController::class, 'question']);
Route::post('/update-rating', function (Request $request) {
    Chat::find($request->chat)->update(['response_rating' => $request->rating]);
});

require __DIR__ . '/auth.php';

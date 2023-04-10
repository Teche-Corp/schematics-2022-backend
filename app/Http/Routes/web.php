<?php

use App\Http\Controllers\WebFeedbackController;
use Illuminate\Support\Facades\Route;

Route::middleware(['iam'])->group(function () {
    Route::get('/feedback', function () {
        return view('form_feedback_team');
    });
    Route::post('/feedback', [WebFeedbackController::class, 'postFeedback']);
});

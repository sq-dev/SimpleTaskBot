<?php

use App\Http\Controllers\NutgramWebhookController;

Route::post('/webhook', NutgramWebhookController::class);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;

Route::apiResource('courses', CourseController::class);
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterSchoolController;

Route::post('/schools', [RegisterSchoolController::class,'create']);
Route::get('/schools/find/{tenant}',[RegisterSchoolController::class,'show']);


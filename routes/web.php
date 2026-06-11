<?php

use Illuminate\Support\Facades\Route;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::withoutMiddleware([
    \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
])->group(function () {
    \Livewire\Livewire::setUpdateRoute(function ($handle) {
        return Route::post('/livewire/update', $handle);
    });
});
<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });

    Route::get('/', function () {
        dd(User::get());
    });
    Route::get('store-user', function () {
        User::create([
            'name' => 'omran',
            'father_name' => 'omran',
            'last_name' => 'omran',
            'role'=>'admin',
            'date_of_birth'=>'2005-12-21',
            'password' => Hash::make('12345678'),
            'email' => "omran@gmail.com"
        ]);
    });
});

Route::get('f',function(){
dd(sys_get_temp_dir());
});
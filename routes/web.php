<?php

use Illuminate\Support\Facades\Route;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::view('create-tenant','teant');

Route::post('store-tenant',function(Request $request){
    
$tenant=Tenant::create([
    'name'=>$request->tenant_name
]);
$tenant->domains()->create([
    'domain'=>$tenant->name
]);
return 1;
})->name('tenant-store');



Route::withoutMiddleware([
    \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
])->group(function () {
    \Livewire\Livewire::setUpdateRoute(function ($handle) {
        return Route::post('/livewire/update', $handle);
    });
});
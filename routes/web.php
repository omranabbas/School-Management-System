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

Route::get('all-users',function(){
 Tenant::all()->runForEach(function(){
       User::create([
        'name'=>'omran1',
        'password'=>Hash::make('12345678'),
        'email'=>"omran1@gmail.com"
    ]);
    });
});
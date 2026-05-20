<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterSchoolRequest;
use App\Models\Tenant;

class RegisterSchoolController extends Controller
{
    public function create(RegisterSchoolRequest $request) {
        // $result = $tenantService->register(
        //     $request->validated()
        // );
        $tenant = Tenant::create([
            'name' => $request->school_name
        ]);
        $domain = $tenant->domains()->create([
            'domain' => $tenant->id . ".localhost"
        ]);
        return response()->json([
            'message' => 'School created successfully',
            'domain' => $domain->domain,
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
            ],
        ], 201);
    }

    public function show(Tenant $tenant)
    {
        return response()->json([
            'message' => 'School find successfully',
            'domain' => $tenant->domains,
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
            ],
        ], 200);
    }
}

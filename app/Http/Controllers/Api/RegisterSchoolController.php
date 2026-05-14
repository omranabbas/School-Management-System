<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterSchoolRequest;
use App\Services\TenantService;

class RegisterSchoolController extends Controller
{
    public function __invoke(
        RegisterSchoolRequest $request,
        TenantService $tenantService
    )
    {
        $result = $tenantService->register(
            $request->validated()
        );

        return response()->json([
            'message' => 'School created successfully',
            'domain' => $result['domain'],
            'tenant' => [
                'id' => $result['tenant']->id,
                'name' => $result['tenant']->name,
            ],
        ], 201);
    }
}
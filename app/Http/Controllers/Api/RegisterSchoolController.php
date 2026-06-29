<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterSchoolRequest;
use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use App\Services\TenantService;
use App\Traits\ApiResponse;

class RegisterSchoolController extends Controller
{
    use ApiResponse;

    public function create(
        RegisterSchoolRequest $request,
        TenantService $tenantService
    ) {
        $result = $tenantService->register(
            $request->validated()
        );

        return $this->successResponse(
            new TenantResource(
                $result['tenant']->load('domains')
            ),
            'School created successfully',
            201
        );
    }

    public function index()
    {
        $tenants = Tenant::with('domains')->get();

        return $this->successResponse(
            TenantResource::collection($tenants),
            'Schools retrieved successfully'
        );
    }

    public function show(Tenant $tenant)
    {
        return $this->successResponse(
            new TenantResource(
                $tenant->load('domains')
            ),
            'School retrieved successfully'
        );
    }
}
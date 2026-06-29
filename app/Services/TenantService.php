<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Str;

class TenantService
{
    public function register(array $data): array
    {
        $tenantId = Str::slug($data['school_name']);

        $tenant = Tenant::create([
            'id' => $tenantId,
            'name' => $data['school_name'],
        ]);

        $tenant->domains()->create([
            'domain' => $tenantId . '.localhost',
        ]);

        return [
            'tenant' => $tenant,
            'domain' => $tenantId . '.localhost',
        ];
    }
}
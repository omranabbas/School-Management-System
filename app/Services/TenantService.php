<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantService
{
    public function register(array $data): array
    {
        $tenantId = Str::slug($data['school_name']);

        $domain = $tenantId . '.localhost';

        $databaseName = 'tenant_' . str_replace('-', '_', $tenantId);

        $tenant = Tenant::create([
            'id' => $tenantId,
            'name' => $data['school_name'],
            'data' => [
                'database' => $databaseName,
            ],
        ]);

        $tenant->domains()->create([
            'domain' => $domain,
        ]);

        DB::statement("CREATE DATABASE IF NOT EXISTS `$databaseName`");
     
        Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->id],
        ]);

        return [
            'tenant' => $tenant,
            'domain' => $domain,
        ];
    }
}
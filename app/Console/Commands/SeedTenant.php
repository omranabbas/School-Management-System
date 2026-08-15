<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Database\Seeders\Tenant\TenantDatabaseSeeder;
use Illuminate\Console\Command;

class SeedTenant extends Command
{
    protected $signature = 'tenants:seed {tenant}';

    protected $description = 'Seed a specific tenant database';

    public function handle(): int
    {
        $tenantId = $this->argument('tenant');

        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            $this->error("Tenant [{$tenantId}] not found.");

            return self::FAILURE;
        }

        $this->info("Seeding tenant: {$tenant->name}");

        tenancy()->initialize($tenant);

        try {

            $this->call('db:seed', [
                '--class' => TenantDatabaseSeeder::class,
                '--force' => true,
            ]);

            $this->newLine();

            $this->info(
                "Tenant [{$tenant->id}] seeded successfully."
            );

        } finally {

            tenancy()->end();
        }

        return self::SUCCESS;
    }
}
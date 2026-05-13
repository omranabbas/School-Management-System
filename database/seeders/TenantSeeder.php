<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::create([
            'id' => 'school1',
            'name' => 'School One',
        ]);

        $tenant->domains()->create([
            'domain' => 'school1.localhost',
        ]);

        tenancy()->initialize($tenant);

        $this->call([
            UserSeeder::class,
        ]);
    }
}
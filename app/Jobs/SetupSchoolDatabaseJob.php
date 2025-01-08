<?php

namespace App\Jobs;

use App\Models\School;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Stancl\Tenancy\Database\DatabaseManager;

class SetupSchoolDatabaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $school;

    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function handle(DatabaseManager $databaseManager)
    {
        try {
            // Create database for tenant
            $databaseManager->createDatabase($this->school->tenant);

            // Initialize tenancy
            tenancy()->initialize($this->school->tenant);

            // Run migrations
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            // Run seeder
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\TenantDatabaseSeeder',
                '--force' => true,
            ]);

            // Assign role to admin user
            $admin = \App\Models\User::where('email', $this->school->admin_email)->first();
            if ($admin) {
                $admin->assignRole('school_admin');
            }

        } catch (\Exception $e) {
            \Log::error('Tenant Setup Failed: ' . $e->getMessage());
            throw $e;
        }
    }
}

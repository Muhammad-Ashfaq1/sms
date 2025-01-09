<?php

namespace App\Jobs;

use App\Models\School;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
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
        Log::info('Starting database setup for school: ' . $this->school->id);

        try {
            // Generate the dynamic database name using school name and today's date
            $databaseName = 'tenant_' . \Str::slug($this->school->name) . '_' . now()->format('Y_m_d');

            // Set the dynamic database name to the school record
            $this->school->database = $databaseName;
            $this->school->save(); // Save the generated database name

            Log::info('Database name set for school: ' . $databaseName);

            // Create the database for the tenant
            $this->school->tenant->createDatabase($databaseName); // Create the database using tenant's information

            Log::info('Database created for school: ' . $this->school->id);

            // Initialize tenancy for this tenant
            tenancy()->initialize($this->school->tenant);

            // Run tenant-specific migrations
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            Log::info('Migrations executed for tenant database: ' . $this->school->database);

            // Run seeder for tenant
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\TenantDatabaseSeeder',
                '--force' => true,
            ]);

            Log::info('Seeder executed for tenant database: ' . $this->school->database);

            // Assign role to the admin user
            $admin = \App\Models\User::where('email', $this->school->admin_email)->first();
            if ($admin) {
                $admin->assignRole('school_admin'); // Assign the role after database setup
                Log::info('Role assigned to admin user: ' . $admin->email);
            }

        } catch (\Exception $e) {
            Log::error('Error occurred during tenant database setup for school: ' . $this->school->id . ' Error: ' . $e->getMessage());
            throw $e; // Rethrow the error to ensure it gets logged and handled
        }
    }
}

<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SetupSchoolDatabaseJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $school;

    /**
     * Create a new job instance.
     */
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Create tenant database
        DB::statement("CREATE DATABASE {$this->school->database}");

        // Switch to the tenant database
        tenancy()->initialize($this->school->id);

        // Run tenant migrations
        Artisan::call('tenants:migrate', [
            '--tenant' => $this->school->id,
        ]);

        // Optional: Seed tenant database
        Artisan::call('tenants:db:seed', [
            '--tenant' => $this->school->id,
        ]);
    }
}

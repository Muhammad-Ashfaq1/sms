<?php

namespace App\Jobs;

use App\Models\Tenant;
use App\Models\Tenant\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SetupTenantJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

    protected $tenant;
    protected $user;
    public function __construct(Tenant $tenant,$user)
    {
        //
        $this->tenant = $tenant;
        $this->user   = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Artisan::call('tenants:migrate', ['--tenants' => $this->tenant->id]);
        Artisan::call('tiedown:permission-sync',['--tenant' => $this->tenant->id]);
        $this->tenant->run(function () {
            $user = User::create([
                'name'     => $this->tenant->org_name,
                'email'    => $this->user['email'],
                'password' => $this->user['password']
            ]);

            $role = Role::where('name','Admin')->first();
            $user->syncRoles([$role->name]);
        });
    }
}

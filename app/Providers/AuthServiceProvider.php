<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Auth, Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //
        Log::info('boot');
        $gate->before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        try {
            $permissions = \App\Models\System\Permission::with('roles')->get();
            Log::info($permissions);
            foreach ($permissions as $permission) {
                $gate->define($permission->name, function($user) use ($permission) {
                    Log::info($permission->name);
                    return $user->hasPermission($permission);
                });
            }
        }
        catch (\Exception $e) {
        }
    }
}

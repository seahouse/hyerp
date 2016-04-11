<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Auth;

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
        // $user = Auth::user();
        // $permissions = \App\Models\System\Permission::get();
        // dd($permissions);
        // $gate->define('aa', function($user) {
        // });

        try {
            $permissions = \App\Models\System\Permission::with('roles')->get();
            foreach ($permissions as $permission) {
                $gate->define($permission->name, function($user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        }
        catch (\Exception $e) {
        }
    }
}

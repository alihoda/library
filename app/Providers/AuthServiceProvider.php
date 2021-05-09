<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Permissions
     * 
     * action => [roles]
     */
    public static $permissions = [
        'admin' => ['admin'],
    ];


    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Global gates

        // Determine if user is admin
        Gate::before(function ($user, $ability) {
            if ($user->role === 'admin') {
                return true;
            }
        });

        // Roles based authorization
        foreach (self::$permissions as $action => $roles) {
            Gate::define($action, function (User $user) use ($roles) {
                if (in_array($user->role, $roles)) {
                    return true;
                }
            });
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        // Book::class => BookPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isGuest', function($user) {
            return $user->role == 'guest';
        });

        Gate::define('isAdmin', function($user) {
            return $user->role == 'admin';
        });

        Gate::define('isAllAdmin', function($user) {
            return $user->role == 'admin' || $user->role == 'super_admin';
        });

        Gate::define('isSuperAdmin', function($user) {
            return $user->role == 'super_admin';
        });
    }
}

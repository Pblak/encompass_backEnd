<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Cast\Object_;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    public function register()
    {
        $this->app->singleton('auth.user_guard', function ($app) {

            foreach (array_keys(config('auth.guards')) as $guard) {
                if (Auth::guard($guard)->check()) {
                    // Get the respective model according to the guard
                    $provider = config('auth.guards.' . $guard . '.provider');
                    $model = config('auth.providers.' . $provider . '.model');
                    $userId = Auth::guard($guard)->id();
                    return (Object) [
                        'user' => $model::find($userId),
                        'guard' => $guard,
                    ];
                }
            }
            return false;
        });
    }



    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

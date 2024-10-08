<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     */
    protected $policies = [
        //
    ];

//    public function register()
//    {
//        $this->app->singleton('auth.user_guard', function ($app) {
//            foreach (array_keys(config('auth.guards')) as $guard) {
////            dump($guard ,'guard');
//                if (Auth::guard($guard)->check()) {
////                    dump(Auth::guard($guard)->check() ,'check');
//
//                    // Get the respective model according to the guard
//                    $provider = config('auth.guards.' . $guard . '.provider');
//                    $model = config('auth.providers.' . $provider . '.model');
//
//                    $userId = Auth::guard($guard)->id();
//                    return (Object) [
//                        'user' => $model::find($userId),
//                        'guard' => $guard,
//                    ];
//                }
//            }
//            return false;
//        });
//    }


    /**
     * Register any authentication / authorization services.
     */

    public function boot(): void
    {
        $this->registerPolicies();



    }
}

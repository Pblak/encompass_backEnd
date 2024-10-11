<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\AuthenticationException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::guard('sanctum')->macro('checkTable', function (string|array $tables) {
            $tables = is_array($tables) ? $tables : explode('|', $tables);

            // Get the authenticated user, or throw a 401 Unauthenticated
            // error if not authenticated
            $user = Auth::user();

            if (!$user) {
                throw new AuthenticationException('Unauthenticated');
            }

            // Get the authenticated user's table
            $userTable = $user->getTable();

            return in_array($userTable, $tables);
        });
    }

}

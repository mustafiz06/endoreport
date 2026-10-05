<?php

namespace App\Providers;

use App\Models\OrganizationSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrapFour();

        // Gate definition: Super Admin bypasses, otherwise checks user permission
        Gate::before(function ($user, string $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }

            return $user->hasPermission($ability) ? true : null;
        });

        // Global view composer for Organization settings in layouts & reports
        View::composer('*', function ($view) {
            if (!app()->runningInConsole()) {
                try {
                    $view->with('currentOrg', OrganizationSetting::current());
                } catch (\Throwable $e) {
                    $view->with('currentOrg', null);
                }
            }
        });
    }
}

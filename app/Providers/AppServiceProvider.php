<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CoreConfig;
use Illuminate\Support\Facades\Schema;

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
        //
        $tableCheck = Schema::hasTable('core_config');;
        if ($tableCheck) {
            view()->share('core', CoreConfig::list());
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // DB::listen(function (QueryExecuted $query) {
        //     $sql = str_replace('?', "'%s'", $query->sql);
        //     $log = count($query->bindings) > 0 ? vsprintf($sql, $query->bindings) : $sql;
        //     Log::info($log);
        // });
    }
}

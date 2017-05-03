<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB, Log;
use App\Models\Product\Item;
use App\Models\Product\Itemclass;
use App\Models\Product\Itemclass_hxold;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
//        DB::listen(function ($query) {
//            Log::info($query->sql);
//        });

//        DB::connection('sqlsrv')->listen(function ($query) {
//            Log::info($query->sql);
//        });

//        Itemclass_hxold::saved(function ($itemclass) {
//            Log::info('itemclass saved.');
//            return true;
//        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

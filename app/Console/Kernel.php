<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        Commands\SendEmails::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

//        Log::info('schedule start.');
//        $schedule->call(function () {
//            DB::table('items')->where('id', 4)->update(['item_name' => '电伴热']);
//        })->everyMinute();
//        Log::info('schedule end.');
    }

    protected function ttt(Schedule $schedule)
    {
        Log::info('ttt start.');
        $schedule->call(function () {
            DB::table('items')->where('id', 4)->update(['item_name' => '电伴热2']);
        });
        Log::info('ttt end.');
    }
}

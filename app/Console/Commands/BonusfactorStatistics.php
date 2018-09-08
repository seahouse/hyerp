<?php

namespace App\Console\Commands;

use App\Models\Sales\Bonusfactor_hxold;
use App\Models\Sales\Salesorder_hxold;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BonusfactorStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:bonusfactor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $soheads = Salesorder_hxold::all();
        foreach ($soheads as $sohead)
        {
            $bonusfactor = new Bonusfactor_hxold();
            $bonusfactor->sohead_id = $sohead->id;
            $bonusfactor->value = $sohead->getBonusfactorByPolicy();
            $bonusfactor->date = Carbon::now();
            $bonusfactor->save();
        }
    }
}

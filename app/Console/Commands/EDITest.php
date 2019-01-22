<?php

namespace App\Console\Commands;

use App\Models\Sales\Bonusfactor_hxold;
use App\Models\Sales\Salesorder_hxold;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EDITest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:edi_read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $filepath = "E:\\project\\docs\\EDI\\PO_SAMPLE_4816249.002823068";
        $file = fopen($filepath, "r") or die("Unable to open file!");
        echo fread($file, filesize($filepath));
        fclose($file);
    }
}

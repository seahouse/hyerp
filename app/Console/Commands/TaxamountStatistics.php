<?php

namespace App\Console\Commands;

use App\Models\Sales\Salesorder_hxold;
use App\Models\Sales\Tem_Taxamountstatistics_hxold;
use Illuminate\Console\Command;
use DB;

class TaxamountStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:taxamount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计: 统计销售订单的税率与采购订单的税率, 保存到指定的数据表';

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
        // clear datatable
        Tem_Taxamountstatistics_hxold::truncate();

        $soheads = Salesorder_hxold::all();
        foreach ($soheads as $sohead)
        {
            $sohead_id = $sohead->id;
            $this->info($sohead_id);
//            $sohead_taxamount = [];
//            DB::connection('foo')->select(...);
//            DB::connection('sqlsrv')->select('select dbo.getSoheadTaxAmount(7537)' );
            $sohead_taxamounts = DB::connection('sqlsrv')->select('select dbo.getSoheadTaxAmount(' . $sohead->id . ') as soheadtaxamount');
//            $sohead_taxamounts = DB::connection('sqlsrv')->select('select dbo.getSoheadTaxAmount(7537) as soheadtaxamount');
            $sohead_taxamount = array_first($sohead_taxamounts)->soheadtaxamount;
            $this->info('  ' . $sohead_taxamount);
//            foreach ($sohead_taxamounts as $sohead_taxamount)
//                $this->info('  ' . $sohead_taxamount->soheadtaxamount);
            $sohead_taxamountofpoheads = DB::connection('sqlsrv')->select('select dbo.getSoheadTaxAmountOfPohead(' . $sohead->id . ') as soheadtaxamountofpohead');
            $sohead_taxamountofpohead = array_first($sohead_taxamountofpoheads)->soheadtaxamountofpohead;
            $this->info('  ' . $sohead_taxamountofpohead);

            $taxamountstatistics_hxold = new Tem_Taxamountstatistics_hxold;
            $taxamountstatistics_hxold->sohead_id = $sohead_id;
            $taxamountstatistics_hxold->sohead_taxamount = $sohead_taxamount;
            $taxamountstatistics_hxold->sohead_poheadtaxamount = $sohead_taxamountofpohead;
            $taxamountstatistics_hxold->save();
        }
    }
}

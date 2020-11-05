<?php

use App\Models\System\Report;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * php artisan db:seed --class=sp_po_supplier_amount_seeder
 */
class sp_po_supplier_amount_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('sqlsrv')->statement("
CREATE PROC pGetSupplierAmountByYear (@year_begin INT = 2016, @year_end INT = 9999)
AS
BEGIN
	WITH T
	AS (
		SELECT ISNULL(B.供应商名称, 'N/A') NAME, A.采购订单金额 AMOUNT, A.编号年份 YEAR
		FROM 采购订单 A
		LEFT JOIN 供应商 B ON A.供应商ID = B.供应商ID
		WHERE A.采购订单金额 > 0 AND A.编号年份 BETWEEN @YEAR_BEGIN AND @YEAR_END
		)
	SELECT NAME, YEAR, SUM(AMOUNT) AMOUNT
	FROM T
	GROUP BY NAME, YEAR
	ORDER BY 2, 3 DESC
END");

        Report::insert([
            'name' => 'po_supplier_amount',
            'module' => '采购',
            'titleshow' => '供应商名称,年份,采购订单金额',
            'active' => 1,
            'autostatistics' => 0,
            'descrip' => '按年统计供应商采购量',
            'statement' => 'exec pGetSupplierAmountByYear',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}

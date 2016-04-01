<?php

use Illuminate\Database\Seeder;

class ReimbursementtypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('reimbursementtypes')->insert([
        	'name' => '业务费',
        	'created_at' => DB::raw('NOW()'), 
			'updated_at' => DB::raw('NOW()'),
        ]);

        DB::table('reimbursementtypes')->insert([
            'name' => '请客费',
            'created_at' => DB::raw('NOW()'), 
            'updated_at' => DB::raw('NOW()'),
        ]);

        DB::table('reimbursementtypes')->insert([
            'name' => '差旅费',
            'created_at' => DB::raw('NOW()'), 
            'updated_at' => DB::raw('NOW()'),
        ]);

        DB::table('reimbursementtypes')->insert([
            'name' => '现场工程支出',
            'created_at' => DB::raw('NOW()'), 
            'updated_at' => DB::raw('NOW()'),
        ]);
    }
}

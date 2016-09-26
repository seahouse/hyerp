<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('permissions')->insert(array(
	        [
	            'name' => 'approval_paymentrequest_create',
	            'display_name' => '供应商付款申请_创建',
	            'description' => '创建供应商付款申请',
	            'created_at' => DB::raw('NOW()'),
	            'updated_at' => DB::raw('NOW()')
	        ],
	        // [
	        //     'name' => 'individual',
	        //     'display_name' => '个人',
	        //     'description' => '个人用户',
	        //     'created_at' => DB::raw('NOW()'),
	        //     'updated_at' => DB::raw('NOW()')
	        // ]
        ));
    }
}

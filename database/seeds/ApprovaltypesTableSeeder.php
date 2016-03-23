<?php

use Illuminate\Database\Seeder;

class ApprovaltypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('approvaltypes')->insert([
        	'name' => '报销',
        	'description' => '',
        	'created_at' => DB::raw('NOW()'), 
			'updated_at' => DB::raw('NOW()'),
        ]);
    }
}

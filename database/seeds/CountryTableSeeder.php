<?php

use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('countrys')->delete();
        
        DB::table('countrys')->insert(array(
        ['name' => '中国', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
        ));
    }
}

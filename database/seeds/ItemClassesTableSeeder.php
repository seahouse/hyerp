<?php

use Illuminate\Database\Seeder;

class ItemClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('itemclasses')->insert([
            'name' => '类型1',
            'descrip' => '',
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),
        ]);
    }
}

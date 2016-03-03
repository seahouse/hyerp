<?php

use Illuminate\Database\Seeder;

class CharacteristicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('chars')->insert(
            [
                'name' => '型号',
                'bitems' => true,
    			'created_at' => DB::raw('NOW()'), 
    			'updated_at' => DB::raw('NOW()'),
			]
        );
    }
}

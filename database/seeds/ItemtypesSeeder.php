<?php

use Illuminate\Database\Seeder;

class ItemtypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('itemtypes')->delete();
        
        DB::table('itemtypes')->insert(array(
            ['name' => '采购', 'descrip' => '', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['name' => '生产', 'descrip' => '', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['name' => '外购', 'descrip' => '', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')]
        ));
    }
}

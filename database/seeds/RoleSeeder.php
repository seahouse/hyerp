<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->delete();
        
        DB::table('roles')->insert(array(
        [
            'name' => 'company',
            'display_name' => '企业',
            'description' => '企业用户',
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()')
        ],
        [
            'name' => 'individual',
            'display_name' => '个人',
            'description' => '个人用户',
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()')
        ]
        ));
    }
}

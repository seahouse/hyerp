<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

//         $this->call('UsersTableSeeder');
        $this->call(UserTableSeeder::class);
        $this->call('ItemtypesSeeder');
        $this->call('RoleSeeder');
        $this->call(ItemClassesTableSeeder::class);
        $this->call(CharacteristicTableSeeder::class);

        Model::reguard();
    }
}

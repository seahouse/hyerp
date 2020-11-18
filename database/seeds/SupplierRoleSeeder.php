<?php

use App\Models\System\Permission;
use App\Models\System\Role;
use App\Models\System\RolePermission;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * 增加供应商角色
 * php artisan db:seed --class=SupplierRoleSeeder
 */
class SupplierRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rId = Role::insertGetId(['name' => 'supplier', 'display_name' => '供应商', 'description' => '供应商', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        $pId = Permission::insertGetId(['name' => 'supplier', 'display_name' => '供应商', 'description' => '供应商', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        RolePermission::create(['permission_id' => $pId, 'role_id' => $rId]);
    }
}

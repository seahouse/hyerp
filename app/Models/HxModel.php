<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 如果使用旧数据库(hxcrm2016)，自动添加数据库前缀
 */
class HxModel extends Model
{
    /**
     * 默认添加数据库前缀
     */
    protected $old_db = true;

    public function __construct()
    {
        $this->table = config('database.hxold_database') . $this->table;
    }
}

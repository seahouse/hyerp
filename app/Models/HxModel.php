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

    /**
     * 读取的config(database.hxold_database)
     */
    protected $db_prefix;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->db_prefix = config('database.hxold_database');
        $this->table = "{$this->db_prefix}{$this->table}";

    }
}

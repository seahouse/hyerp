<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDtusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('dtusers', function (Blueprint $table) {
            //
            $table->increments('id');

            $table->integer('user_id');
            $table->string('userid')->nullable();               // 员工唯一标识ID
            $table->string('name')->nullable();                 // 成员名称
            $table->string('tel')->nullable();                  // 分机号
            $table->string('workPlace')->nullable();            // 办公地点
            $table->string('remark')->nullable();               // 备注
            $table->string('mobile')->nullable();               // 手机号码
            $table->string('email')->nullable();                // 员工的电子邮箱
            $table->string('orgEmail')->nullable();             // 员工的企业邮箱
            $table->string('active')->nullable();               // 是否已经激活, true表示已激活, false表示未激活
            $table->string('orderInDepts')->nullable();         // 在对应的部门中的排序, Map结构的json字符串, key是部门的Id, value是人员在这个部门的排序值
            $table->string('isAdmin')->nullable();              // 是否为企业的管理员, true表示是, false表示不是
            $table->string('isBoss')->nullable();               // 是否为企业的老板, true表示是, false表示不是
            $table->string('dingId')->nullable();               // 钉钉Id
            $table->string('isLeaderInDepts')->nullable();      // 在对应的部门中是否为主管, Map结构的json字符串, key是部门的Id, value是人员在这个部门中是否为主管, true表示是, false表示不是
            $table->string('isHide')->nullable();               // 是否号码隐藏, true表示隐藏, false表示不隐藏
            $table->string('department')->nullable();           // 成员所属部门id列表
            $table->string('position')->nullable();             // 职位信息
            $table->string('avatar')->nullable();               // 头像url
            $table->string('jobnumber')->nullable();            // 员工工号
            $table->string('extattr')->nullable();              // 扩展属性，可以设置多种属性(但手机上最多只能显示10个扩展属性，具体显示哪些属性，请到OA管理后台->设置->通讯录信息设置和OA管理后台->设置->手机端显示信息设置)性

            $table->timestamps();

            $table->unique('userid');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });                    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('dtusers');
    }
}

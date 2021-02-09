<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnualbonussheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annualbonussheets', function (Blueprint $table) {
            $table->increments('id');

            $table->date('salary_date');
            $table->string('username');                                  				// 姓名
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('department')->default('');                                  // 部门
            $table->decimal('salaryincrease')->default(0.0);                           // 增长工资
            $table->decimal('months')->default(0.0);                                    // 月份（月数）
            $table->decimal('yearend_salary')->default(0.0);                            // 年终工资
            $table->decimal('performance_salary')->default(0.0);                        // 绩效工资
            $table->decimal('yearend_bonus')->default(0.0);                             // 年终奖金
            $table->decimal('duty_subsidy')->default(0.0);                              // 职务补贴
            $table->decimal('duty_allowance')->default(0.0);                           // 职称津贴
            $table->decimal('forum_amount')->default(0.0);                              // 座谈会
            $table->decimal('other_amount')->default(0.0);                               // 其他
            $table->decimal('boss_prize')->default(0.0);                                // 老板奖
            $table->decimal('amount')->default(0.0);                                    // 发放金额
            $table->decimal('goodemployee_amount')->default(0.0);                      // 优秀员工
            $table->decimal('totalamount')->default(0.0);                               // 合计
            $table->decimal('borrow_wages')->default(0.0);                               // 借款扣回
            $table->decimal('individualincometax_amount')->default(0.0);                // 个税
            $table->decimal('actual_amount')->default(0.0);                             // 实际发放
            $table->string('remark')->default('');                                        // 备注

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('annualbonussheets');
    }
}

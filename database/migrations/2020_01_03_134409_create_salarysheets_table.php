<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalarysheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salarysheets', function (Blueprint $table) {
            $table->increments('id');

            $table->date('salary_date');
            $table->string('username');                                  				// 姓名
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('department')->default('');                                  // 部门
            $table->decimal('attendance_days')->default(0.0);                           // 出勤天数
            $table->decimal('basicsalary')->default(0.0);                               // 基本工资
            $table->decimal('overtime_hours')->default(0.0);                            // 加班小时
            $table->decimal('absenteeism_reduce')->default(0.0);                        // 缺勤减扣
            $table->decimal('paid_hours')->default(0.0);                                    // 计薪小时
            $table->decimal('overtime_amount')->default(0.0);                           // 加班费
            $table->decimal('fullfrequently_award')->default(0.0);                      // 满勤奖
            $table->decimal('meal_amount')->default(0.0);                               // 餐贴
            $table->decimal('car_amount')->default(0.0);                                // 车贴
            $table->decimal('business_amount')->default(0.0);                           // 外差补贴
            $table->decimal('additional_amount')->default(0.0);                         // 补资
            $table->decimal('house_amount')->default(0.0);                              // 房贴
            $table->decimal('hightemperature_amount')->default(0.0);                    // 高温费
            $table->decimal('shouldpay_amount')->default(0.0);                          // 应发工资
            $table->decimal('borrowreduce_amount')->default(0.0);                       // 借款扣回
            $table->decimal('personalsocial_amount')->default(0.0);                     // 个人社保
            $table->decimal('personalaccumulationfund_amount')->default(0.0);           // 个人公积金
            $table->decimal('individualincometax_amount')->default(0.0);                // 个人所得税
            $table->decimal('actualsalary_amount')->default(0.0);                       // 实发工资
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
        Schema::drop('salarysheets');
    }
}

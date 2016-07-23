<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// 报销
class CreateReimbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('reimbursementtype_id')->nullable();        // 报销类别
            $table->date('date');                           // 申请日期
            $table->string('number');                       // 报销编号
            $table->decimal('amount', 18, 4)->default(0.0);               // 报销金额
            $table->integer('customer_id')->nullable();                 // 客户
            $table->string('contacts')->nullable();                     // 客户联系人
            $table->string('contactspost')->nullable();                 // 客户联系人职务
            $table->integer('order_id')->nullable();                    // 对应订单
            $table->integer('status')->default(0);          // 报销状态: 0:初始状态, 1:初审通过, 2: 复审通过, 10:终审通过, -1:初审失败, -2: 复审失败, -10:终审失败
            $table->string('statusdescrip')->nullable();    // 状态描述
            $table->string('descrip');                      // 明细说明
            $table->date('datego')->nullable();             // 出差去日
            $table->date('dateback')->nullable();           // 出差回日
            $table->decimal('mealamount', 18, 4)->default(0.0);                 // 伙食补贴，废弃
            $table->decimal('ticketamount', 18, 4)->default(0.0);               // 车船费，废弃
            $table->decimal('amountAirfares', 18, 4)->default(0.0);             // 飞机票金额
            $table->decimal('amountTrain', 18, 4)->default(0.0);                // 火车票金额
            $table->decimal('amountTaxi', 18, 4)->default(0.0);                 // 出租车金额
            $table->decimal('amountOtherTicket', 18, 4)->default(0.0);          // 其他交通金额
            $table->decimal('stayamount', 18, 4);           // 住宿费
            $table->decimal('otheramount', 18, 4)->default(0.0);          // 其他费用
            $table->integer('approvaler1_id')->nullable();  // 审批人1（出纳，初审）
            $table->date('approvaldate1')->nullable();      // 审批日期1
            $table->integer('approvaler2_id')->nullable();  // 审批人2（销售副总，复审）
            $table->date('approvaldate2')->nullable();      // 审批日期2
            $table->integer('approvaler3_id')->nullable();  // 审批人3（总经理，终审）
            $table->date('approvaldate3')->nullable();      // 审批日期3
            $table->integer('applicant_id');                // 申请人
            $table->integer('approversetting_id')->nullable();          // 下一个审批流程id, 0表示已经走完流程, -1表示没有流程可以走
            
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
        Schema::drop('reimbursements');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiddinginformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biddinginformations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('number');
            $table->integer('year');
            $table->integer('digital_number');
            $table->smallInteger('closed')->default(0);

//            $table->integer('seq');
//            $table->date('bidding_date');
//            $table->string('name');
//            $table->string('scale');
//            $table->string('bidding_kaiping');
//            $table->string('winning_bidder');
//            $table->integer('bidding_touchan')->default(0);
//            $table->string('bidding_luxing');
//            $table->decimal('execution_cost')->default(0.0);
//            $table->decimal('total_steel_consumption')->default(0.0);
//            $table->string('bidding_xishouta');
//            $table->string('bidding_taxing');
//            $table->string('bidding_tadunwei');
//            $table->decimal('bidding_yanqiliang')->default(0.0);
//            $table->decimal('bidding_biaoyan')->default(0.0);
//            $table->string('bidding_fuhe');
//            $table->decimal('temperature')->default(0.0);
//            $table->string('bidding_interface');
//            $table->string('bidding_jingdao');
//            $table->string('bidding_wuhuaqi');
//            $table->string('bidding_hulu');
//            $table->string('bidding_yupeng');
//            $table->string('bidding_feishuifou');
//            $table->string('bidding_lengqueshuibeng');
//            $table->string('bidding_jianwenshuibeng');
//            $table->string('bidding_gupen');
//            $table->integer('area');
//            $table->integer('bidding_huidoushu');
//            $table->string('bidding_paidaishi');
//            $table->string('bidding_daixing');
//            $table->string('bidding_chuchenqihoudu');
//            $table->string('bidding_zhongliang');
//            $table->string('bidding_lvdai');
//            $table->integer('bidding_longgu')->default(0);
//            $table->integer('bidding_tiaoshibudai')->default(1);
//            $table->string('bidding_kongjianju');
//            $table->string('bidding_refeng');
//            $table->string('bidding_equipment_brand');
//            $table->string('bidding_chuchengqita');
//            $table->string('bidding_dianchucheng');
//            $table->string('bidding_zhijiangcang');
//            $table->string('bidding_jiangguan');
//            $table->string('bidding_chuguan');
//            $table->string('bidding_shuixiang');
//            $table->string('bidding_jiangyebeng');
//            $table->string('bidding_zhendoushai');
//            $table->string('bidding_jiangguan');
//            $table->string('bidding_jiangtiaofa');
//            $table->string('bidding_qingyanghuanapenshe');
//            $table->string('bidding_yupentu');
//            $table->string('bidding_fanfencang');
//            $table->string('bidding_guanlu');
//            $table->string('bidding_huoxingtan');
//            $table->string('bidding_guanlu');                               // ?? same
//            $table->string('bidding_guabanjidouti');
//            $table->string('bidding_gongyongguabangaodu');
//            $table->string('bidding_huiku');
//            $table->string('bidding_wendinghua');
//            $table->string('bidding_shuinicang');
//            $table->string('bidding_aohejiyuanyechuguan');                  // 螯合剂原液储罐
//            $table->string('bidding_aohejizhibeiguan');
//            $table->string('bidding_aohejichuguan');
//            $table->string('bidding_feihuishuixiang');

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
        Schema::drop('biddinginformations');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_order', function (Blueprint $table) {
            $table->increments('id')->comment('订单id');
            $table->integer('user_id')->nullable()->default(0)->comment('所属人员id');
            $table->string('goods_id',64)->nullable()->comment('拥有商品id');
            $table->string('num',64)->nullable()->comment('对应商品数量');
            $table->tinyInteger('status')->nullable()->default(0)
                ->comment('订单状态：0-待发货 1-已发货 2-已确认 3-退货申请 4-已退货');
            $table->string('numbering',64)->nullable()->comment('订单编号');
            $table->integer('courier_id')->nullable()->default(0)->comment('快递方式');
            $table->decimal('amount',10,2)->nullable()->default(0)->comment('订单金额');
            $table->string('courier_num',64)->nullable()->comment('快递编号');
            $table->timestamp('time')->nullable()->comment('发货时间');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_order');
    }
}

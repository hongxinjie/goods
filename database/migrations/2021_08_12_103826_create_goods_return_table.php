<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_return', function (Blueprint $table) {
            $table->increments('id')->comment('退货id');
            $table->integer('user_id')->nullable()->default(0)->comment('所属人员id');
            $table->string('goods_id',64)->nullable()->comment('商品id');
            $table->string('num',64)->nullable()->comment('对应商品数量');
            $table->string('numbering',64)->nullable()->comment('订单编号');
            $table->decimal('amount',10,2)->nullable()->comment('订单价格');
            $table->text('reason')->nullable()->comment('退货理由');
            $table->tinyInteger('status')->nullable()->default(0)->comment('退货状态 0-待审核 1-已拒绝 2-已同意');
            $table->integer('courier_id')->nullable()->default(0)->comment('快递方式');
            $table->string('courier_num',64)->nullable()->comment('快递编号');
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
        Schema::dropIfExists('goods_return');
    }
}

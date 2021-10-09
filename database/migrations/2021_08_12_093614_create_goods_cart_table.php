<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_cart', function (Blueprint $table) {
            $table->increments('id')->comment('购物车id');
            $table->integer('goods_id')->nullable()->default(0)->comment('商品id');
            $table->integer('num')->nullable()->default(0)->comment('商品数量');
            $table->decimal('amount',10,2)->nullable()->default(0)->comment('商品价格');
            $table->integer('user_id')->nullable()->default(0)->comment('所属人员id');
            $table->tinyInteger('status')->nullable()->default(0)->comment('状态 0-未下单 1-已下单');
            $table->string('numbering',64)->nullable()->comment('订单编号');
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
        Schema::dropIfExists('goods_cart');
    }
}

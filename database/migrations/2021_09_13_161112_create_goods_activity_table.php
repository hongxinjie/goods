<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50)->comment('活动名称');
            $table->string('goods_id',64)->nullable()->comment('活动商品');
            $table->text('description')->nullable()->comment('活动描述');
            $table->tinyInteger('type')->nullable()->default(0)->comment('活动类型 0-买一送一 1-限时折扣 2-限时秒杀');
            $table->tinyInteger('status')->nullable()->default(0)->comment('是否上架 0-上架 1-下架');
            $table->tinyInteger('state')->nullable()->default(0)->comment('状态 0-待进行 1-进行中 2-已截止');
            $table->decimal('discount',10,2)->nullable()->default(0.00)->comment('折扣');
            $table->decimal('money',10,2)->nullable()->default(0.00)->comment('秒杀价格');
            $table->integer('count')->nullable()->comment('每人限购数量');
            $table->dateTime('start')->nullable()->comment('开始时间');
            $table->dateTime('end')->nullable()->comment('结束时间');
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
        Schema::dropIfExists('goods_activity');
    }
}

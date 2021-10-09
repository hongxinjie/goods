<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_info', function (Blueprint $table) {
            $table->increments('id')->comment('商品id');
            $table->string('name',100)->nullable()->comment('商品名称');
            $table->integer('menu_id')->nullable()->default(0)->comment('商品所属分类');
            $table->string('label_id',64)->nullable()->comment('商品标签');
            $table->integer('num')->nullable()->default(0)->comment('商品数量');
            $table->decimal('amount',10,2)->nullable()->default(0)->comment('商品价格');
            $table->text('detail')->nullable()->comment('商品详情');
            $table->string('file_path',255)->comment('商品图片地址');
            $table->tinyInteger('status')->default(1)->nullable()->comment('是否上架 0-下架 1-上架');
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
        Schema::dropIfExists('goods_info');
    }
}

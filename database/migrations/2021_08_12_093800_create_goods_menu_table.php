<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_menu', function (Blueprint $table) {
            $table->increments('id')->comment('分类id');
            $table->string('title',64)->comment('分类名称');
            $table->integer('parent_id')->nullable()->default(0)->comment('父级id');
            $table->integer('order')->nullable()->default(0)->comment('排序');
            $table->text('description')->nullable()->comment('分类描述');
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
        Schema::dropIfExists('goods_menu');
    }
}

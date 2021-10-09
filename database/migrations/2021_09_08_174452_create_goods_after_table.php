<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsAfterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_after', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50)->comment('名称');
            $table->string('code',20)->nullable()->comment('邮编');
            $table->string('phone',20)->nullable()->comment('联系电话');
            $table->string('address',255)->nullable()->comment('地址');
            $table->string('file_path','255')->nullable()->comment('图片');
            $table->text('introduce')->nullable()->comment('介绍');
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
        Schema::dropIfExists('goods_after');
    }
}

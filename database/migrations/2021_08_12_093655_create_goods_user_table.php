<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_user', function (Blueprint $table) {
            $table->increments('id')->comment('会员id');
            $table->string('username',255)->comment('用户名');
            $table->string('password',255)->comment('密码');
            $table->string('address',255)->comment('地址');
            $table->string('phone',20)->comment('联系电话');
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
        Schema::dropIfExists('goods_user');
    }
}

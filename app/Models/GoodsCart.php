<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GoodsCart extends Model
{
	use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'goods_cart';

    public  const  STATUS_ZERO = 0;
    public  const  STATUS_ONE = 1;
    public const  status = [
        self::STATUS_ZERO => '未下单',
        self::STATUS_ONE => '已下单'
    ];

}

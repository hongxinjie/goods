<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GoodsOrder extends Model
{
	use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'goods_order';

    public const STATUS_WAIT = 0 ;
    public const STATUS_THEN = 1;
    public const STATUS_CONFIRM = 2;
    public const STATUS_APPLY = 3;
    public const STATUS_RETURN = 4;

    public const STATUS = [
        self::STATUS_WAIT => '待发货',
        self::STATUS_THEN => '已发货',
        self::STATUS_CONFIRM => '已确认',
        self::STATUS_APPLY => '退货申请',
        self::STATUS_RETURN => '已退货',
    ];
}

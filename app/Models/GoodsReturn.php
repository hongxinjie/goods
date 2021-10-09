<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GoodsReturn extends Model
{
	use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'goods_return';

    public const STATUS_ZERO = 0;
    public const STATUS_ONE = 1;
    public const STATUS_TWO = 2;

    public const status = [
        self::STATUS_ZERO => '未审核',
        self::STATUS_ONE => '已拒绝',
        self::STATUS_TWO => '已同意'
    ];

}

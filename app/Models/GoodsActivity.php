<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GoodsActivity extends Model
{
	use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'goods_activity';

    public const STATE_WHEN = 0;
    public const STATE_ON = 1;
    public const STATE_END = 2;

    public const STATE = [
        self::STATE_WHEN => '待进行',
        self::STATE_ON => '进行中',
        self::STATE_END => '已截止'
    ];

}

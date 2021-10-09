<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Dcat\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class GoodsMenu extends Model
{
	use HasDateTimeFormatter;
	use ModelTree;
    protected $table = 'goods_menu';
}

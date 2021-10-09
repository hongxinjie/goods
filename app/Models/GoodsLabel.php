<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class GoodsLabel extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'goods_label';
    
}

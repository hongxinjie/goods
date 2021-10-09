<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
	use HasDateTimeFormatter;    
    public $timestamps = false;

}

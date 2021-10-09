<?php

namespace App\Admin\Renderable;

use App\Models\GoodsInfo;
use App\Models\GoodsOrder;
use Dcat\Admin\Support\LazyRenderable;
use Dcat\Admin\Widgets\Table;
use Faker\Factory;

class GoodsInfoTable extends LazyRenderable
{
    public function render()
    {
        $data = [];

        $id = GoodsOrder::whereId($this->key)->value('goods_id');
        $goods_id = explode(',',$id);
        $num = GoodsOrder::whereId($this->key)->value('num');
        $goods_num = explode(',' , $num);
        //合并数组 一个为key 一个为value
        $goods_list = array_combine($goods_id,$goods_num);
        foreach ($goods_list as $key => $value) {
            $info = GoodsInfo::whereId($key)->first();
            $data[] = [
                '商品名称' => $info->name,
                '商品数量' => $value,
                '商品单价' => $info->amount
            ];
        }

        return Table::make(['商品名称', '商品数量', '商品单价'], $data);
    }
}

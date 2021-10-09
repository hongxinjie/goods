<?php

namespace App\Admin\Renderable;


use App\Models\GoodsInfo;
use App\Models\GoodsReturn;
use App\Models\GoodsUser;
use Dcat\Admin\Support\LazyRenderable;
use Dcat\Admin\Widgets\Table;
use Faker\Factory;

class GoodsReturnTable extends LazyRenderable
{
    public function render()
    {
        $id = $this->key;

        $data = GoodsReturn::whereId($id)
            ->get(
                [
                    'id',
                    'user_id',
                    'goods_id',
                    'num',
                    'numbering',
                    'amount',
                    'reason',
                    'created_at'
                ]
            )
            ->toArray();
        $data[0]['user_id'] = GoodsUser::whereId($data[0]['user_id'])->value('username');
        $goods_id = explode(',', $data[0]['goods_id']);
        $goods = [];
        foreach ($goods_id as $value) {
            $goods = GoodsInfo::whereId($value)->value('name');
        }
        $data[0]['goods_id'] = $goods;

        $titles = [
            'id',
            '用户',
            '商品',
            '数量',
            '订单编号',
            '订单价格',
            '退货理由',
            '申请时间'
        ];


        return Table::make($titles, $data);
    }
}

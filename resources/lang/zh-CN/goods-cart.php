<?php
return [
    'labels' => [
        'GoodsCart' => '购物车',
        'goods-cart' => '购物车',
    ],
    'fields' => [
        'goods_id' => '商品名称',
        'num' => '商品数量',
        'amount' => '商品价格',
        'user_id' => '所属人员',
        'numbering' => '订单编号',
        'status' => '状态'
    ],
    'options' => [
        'status' => [
            0 => '未下单',
            1 => '已下单'
        ]
    ],
];

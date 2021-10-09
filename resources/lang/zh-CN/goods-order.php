<?php
return [
    'labels' => [
        'GoodsOrder' => '订单信息',
        'goods-order' => '订单信息',
    ],
    'fields' => [
        'user_id' => '所属人员',
        'goods_id' => '商品',
        'status' => '订单状态',
        'numbering' => '订单编号',
        'courier_id' => '快递方式',
        'amount' => '订单金额',
        'courier_num' => '快递编号',
        'time' => '发货时间',
        'num' => '商品数量'
    ],
    'options' => [
        'status' => [
            0 => '待发货',
            1 => '已发货',
            2 => '已确认',
            3 => '退货申请',
            4 => '已退货',
        ]
    ],
];

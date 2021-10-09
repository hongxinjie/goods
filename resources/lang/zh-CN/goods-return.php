<?php
return [
    'labels' => [
        'GoodsReturn' => '退货申请',
        'goods-return' => '退货申请',
    ],
    'fields' => [
        'user_id' => '所属人员',
        'goods_id' => '商品',
        'numbering' => '订单编号',
        'amount' => '订单价格',
        'status' => '状态',
        'reason' => '退货理由',
    ],
    'options' => [
        'status' => [
            0 => '未审核',
            1 => '已拒绝',
            2 => '已同意'
        ]
    ],
];

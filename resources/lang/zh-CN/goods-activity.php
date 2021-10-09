<?php
return [
    'labels' => [
        'GoodsActivity' => '活动中心',
        'goods-activity' => '活动中心',
    ],
    'fields' => [
        'name' => '活动名称',
        'goods_id' => '活动商品',
        'description' => '活动描述',
        'type' => '活动类型',
        'discount' => '活动折扣(%)',
        'money' => '秒杀价格',
        'count' => '每人限购数量',
        'state' => '活动状态',
        'status' => '是否上架',
        'start' => '开始时间',
        'end' => '结束时间',
    ],
    'options' => [
        'status' => [
            0 => '上架',
            1 => '下架'
        ],
        'state' => [
            0 => '待进行',
            1 => '进行中',
            2 => '已截止'
        ],
        'type' => [
            0 => '买一送一',
            1 => '限时折扣',
            2 => '限时秒杀'
        ]
    ],
];

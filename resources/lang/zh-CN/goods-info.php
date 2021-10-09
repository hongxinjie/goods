<?php
return [
    'labels' => [
        'GoodsInfo' => '商品列表',
        'goods-info' => '商品列表',
        'upload' => '商品导入'
    ],
    'fields' => [
        'name' => '商品名称',
        'menu_id' => '商品所属分类',
        'label_id' => '商品标签',
        'num' => '商品数量',
        'amount' => '商品价格',
        'detail' => '商品详情',
        'file_path' => '商品图片地址',
        'status' => '是否上架',
    ],
    'options' => [
        'status' => [
            0 => '下架',
            1 => '上架'
        ]
    ],
];

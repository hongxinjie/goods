<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
//第一种
//    $router->prefix('goods-info')->group(function ($router) {
//        $router->any('/', 'GoodsInfoController@index');
//        $router->get('/create', 'GoodsInfoController@form');
//
//        $router->get('{id}/edit', 'GoodsInfoController@edit');
//        $router->post('/store', 'GoodsInfoController@store');
//        $router->put('/update/{id}', 'GoodsInfoController@update');
//        $router->delete('{id}', 'GoodsInfoController@destroy');
//    });
//第二种
    //商品列表
    $router->prefix('goods-info')->group(
        function ($router) {
            $router->any('/', 'GoodsInfoController@index');
            $router->get('/create', 'GoodsInfoController@create');
            $router->get('{id}/edit', 'GoodsInfoController@edit');
            $router->get('{id}', 'GoodsInfoController@show');
            $router->post('/store', 'GoodsInfoController@store');
            $router->put('/update/{id}', 'GoodsInfoController@update');
            $router->any('/upload', 'GoodsInfoController@upload');
            $router->any('/download', 'GoodsInfoController@download');
        }
    );
    //商品分类
    $router->resource('/goods-menu','GoodsMenuController');
    //商品标签
    $router->resource('/goods-label','GoodsLabelController');
    //商品活动
    $router->resource('/goods-activity','GoodsActivityController');
    //商品订单
    $router->resource('/goods-order','GoodsOrderController');
    //退货申请
    $router->resource('/goods-return','GoodsReturnController');
    //用户管理
    $router->resource('/goods-user','GoodsUserController');
    //快递管理
    $router->resource('/goods-courier','GoodsCourierController');
    //购物车
    $router->resource('/goods-cart','GoodsCartController');
    //下单
    $router->post('goods-cart/place_order', 'GoodsCartController@placeOrder');
    //三级联动
    $router->get('api/city','GoodsUserController@city');
    $router->get('api/area','GoodsUserController@area');
    //活动管理
    $router->resource('/goods-activity', 'GoodsActivityController');
    //关于我们
    $router->resource('/goods-after','GoodsAfterController');
    $router->post('/store', 'GoodsAfterController@store');

    $router->prefix('goods-activity')->group(
        function ($router) {
            $router->get('/state/{id}', 'GoodsActivityController@state');
        }
    );

});

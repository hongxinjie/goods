<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\ReturnGoods;
use App\Admin\Actions\Grid\Ship;
use App\Admin\Renderable\GoodsInfoTable;
use App\Models\GoodsCourier;
use App\Models\GoodsInfo;
use App\Models\GoodsOrder;
use App\Models\GoodsReturn;
use App\Models\GoodsUser;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class GoodsOrderController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new GoodsOrder(), function (Grid $grid) {
            $grid->model()->orderBy('id','DESC');
//            $grid->fixColumns(2,0);
//            $grid->setActionClass(Grid\Displayers\DropdownActions::class);
            $grid->column('user_id')->display(
                function () {
                    return GoodsUser::whereId($this->user_id)->value('username');
                }
            );
            $grid->goods_id
                ->display(
                    function () {
                        $goods_ids = explode(',', $this->goods_id);
                        return GoodsInfo::whereIn('id', $goods_ids)->pluck('name');
                    }
                )->label('blue2');
            $grid->num;
            $grid->column('info','商品详情')->expand(GoodsInfoTable::make());
            $grid->column('status')->using(admin_trans('goods-order.options.status'))->label(
                [
                    'default' => 'primary',
                    2 => 'success',
                    3 => 'danger',
                    4 => 'success'
                ]
            );
            $grid->column('numbering')->copyable();
            $grid->column('amount');
            $grid->column('courier_id')->display(
                function () {
                    return GoodsCourier::whereId($this->courier_id)->value('name');
                }
            );
            $grid->column('courier_num')->copyable();
            $grid->column('time');
            $grid->column('created_at');

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->row->status == GoodsOrder::STATUS_WAIT) {
                    $actions->append(new Ship());
                }
                if ($actions->row->status == GoodsOrder::STATUS_WAIT || $actions->row->status == GoodsOrder::STATUS_THEN) {
                    $actions->append(new ReturnGoods());
                }
            });

            $grid->export($this->exportTitle())->rows(
                function (array $rows) {
                    $status = GoodsOrder::STATUS;
                    foreach ($rows as $index => &$row ){
                        $row['user_id'] = GoodsUser::whereId($row['user_id'])->value('username');
                        $goods_id = explode(',',$row['goods_id']);
                        $goods_name = GoodsInfo::whereIn('id', $goods_id)->pluck('name')->toArray();
                        $row['goods_id'] = implode(',', $goods_name);
                        $row['status'] = $status[$row['status']];
                        if (empty($row['numbering'])){
                            $row['numbering'] = '';
                        }
                        if (empty($row['courier_num'])){
                            $row['courier_num'] = '';
                        }
                        if (empty($row['time'])){
                            $row['time'] = '';
                        }
                        if (!empty($row['courier_id'])) {
                            $row['courier_id'] = GoodsCourier::whereId($row['courier_id'])->value('name');
                        }
                    }
                    return $rows;
                }
            )->filename('订单信息');

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal('user_id')->select(GoodsUser::all()->pluck('username','id'))->width(4);
                $filter->equal('status')->select(admin_trans('goods-order.options.status'))->width(4);
                $filter->like('numbering')->width(4);
                $filter->equal('courier_id')->select(GoodsCourier::all()->pluck('name','id'))->width(4);
                $filter->like('courier_num')->width(4);
                $filter->between('time')->datetime()->width(6);
                $filter->between('created_at')->datetime()->width(6);
            });

        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new GoodsOrder(), function (Show $show) {
            $show->field('id');
            $show->field('user_id')->as(
                function ($id) {
                    return GoodsUser::whereId($id)->pluck('username');
                }
            )->label('blue2');
            $show->field('goods_id')->as(
                function ($value) {
                    $ids = explode(',',$value);
                    return GoodsInfo::whereIn('id',$ids)->pluck('name');
                }
            )->label('blue2');
            $show->field('status')->using(admin_trans('goods-order.options.status'))->label();
            $show->field('numbering');
            $show->field('courier_id')->as(
                function ($id) {
                    return GoodsCourier::whereId($id)->pluck('name');
                }
            )->label('blue2');
            $show->field('amount');
            $show->field('created_at');
            $show->field('updated_at');

            $show->disableDeleteButton();
            $show->disableEditButton();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new GoodsOrder(), function (Form $form) {
            $form->display('id');
            if ($form->isCreating()) {
                $form->select('user_id')->options(
                    function () {
                        return GoodsUser::all()->pluck('username', 'id');
                    }
                );
                $form->hidden('numbering')->value(time().rand(0,9999));
                $form->multipleSelect('goods_id')->options(
                    function () {
                        return GoodsInfo::all()->pluck('name', 'id');
                    }
                )->saving(
                    function ($value) {
                        return implode(',', $value);
                    }
                );
                $form->text('num')->help("1.商品数量应和上面的商品对应<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.用 ' , ' 号分隔");
            }
            if ($form->isEditing()) {
                $form->hidden('user_id');
                $form->text('username')->value(GoodsUser::whereId('user_id')->pluck('username'));
                $form->text('numbering');
                $form->multipleSelect('goods_id')->options(
                    function () {
                        $goods_ids = explode(',' , $this->goods_id);
                        return GoodsInfo::whereIn('id', $goods_ids)->pluck('name', 'id');
                    }
                )->saving(
                    function ($value) {
                        return implode(',', $value);
                    }
                );
                $form->text('num')->help('1.商品数量应和上面的商品对应<br>
                                                      2.用,号分隔');
                $form->select('status')->options(admin_trans('goods-order.options.status'));
                $form->text('courier_id');
            }

            $form->hidden('amount');
            $form->display('created_at');
            $form->display('updated_at');

            $form->saving(function (Form $form) {
                $form->deleteInput('username');
                $id = $form->goods_id;
                $num = explode(',',$form->num);
                //取出对应商品的价格
                foreach ($id as $item) {
                    if ($item != null) {
                        $amount[] = GoodsInfo::whereId($item)->value('amount');
                    }
                }
                //总价格= 商品价格*对应商品数量 的值相加
                $count = 0 ;
                foreach ($amount as $key => $value) {
                   $sum = bcmul($amount[$key],$num[$key]);
                   $count += $sum;
                }
                $form->amount = $count;
            });
            $form->saved(
                function (Form $form) {
                    return redirect(admin_url('/goods-order'));
                }
            );
            //去除顶部按钮
            $form->disableDeleteButton();
            $form->disableViewButton();
            //去除底部按钮
            $form->footer(function ($footer) {
                $footer->disableViewCheck();
                $footer->disableCreatingCheck();
                $footer->disableEditingCheck();
            });
        });
    }

    public function exportTitle()
    {
        return [
            'id' => '订单ID',
            'user_id' => '所属用户',
            'goods_id' => '商品名称',
            'num' => '商品数量',
            'info' => '商品详情',
            'status' => '订单状态',
            'numbering' => '订单编号',
            'amount' => '订单价格/元',
            'courier_id' => '快递方式',
            'courier_num' => '快递编号',
            'time' => '发货时间',
            'created_at' => '下单时间'
        ];
    }
}

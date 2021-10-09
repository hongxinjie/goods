<?php

namespace App\Admin\Controllers;

use App\Models\GoodsCart;
use App\Models\GoodsInfo;
use App\Models\GoodsOrder;
use App\Models\GoodsUser;
use App\Models\User;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoodsCartController extends AdminController
{

    public function index(Content $content)
    {
        Admin::script(
            <<<JS
                $('.grid__actions__').on('click', '.place', function () {
                    var id = $(this).attr('data-id');
                    var user_id = $(this).attr('data-user');
                    var goods_id = $(this).attr('data-goods');
                    var num = $(this).attr('data-num');
                    var amount = $(this).attr('data-amount');
                    $.ajax({
                        type : 'POST',
                        url : "/admin/goods-cart/place_order",
                        data :
                        {
                            id : id,
                            user_id : user_id,
                            goods_id : goods_id,
                            num : num,
                            amount : amount
                        },
                        success:function (data){
                            if (data.code == 'error') {
                                Dcat.error(data.msg);
                            } else {
                                Dcat.success(data.msg);
                                window.location.reload();
                            }
                        }
                    })
                });
JS

        );
        return $content
            ->title('购物车')
            ->description('List')
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(
            new GoodsCart(),
            function (Grid $grid) {
                $grid->column('user_id')->display(
                    function () {
                        return GoodsUser::whereId($this->user_id)->value('username');
                    }
                );
                $grid->column('goods_id')->display(
                    function () {
                        $goods_id = explode(',', $this->goods_id);
                        return GoodsInfo::whereIn('id', $goods_id)->pluck('name');
                    }
                )->label('blue2');
                $grid->column('num');
                $grid->column('amount');
                $grid->column('numbering')->copyable();
                $grid->column('status')->using(admin_trans('goods-cart.options.status'))->label(
                    [
                        'default' => 'primary',
                        0 => 'danger',
                        1 => 'success',
                    ]
                );
                $grid->column('created_at');

                $grid->filter(
                    function (Grid\Filter $filter) {
                        $filter->equal('id');
                    }
                );

                $grid->actions(
                    function (Grid\Displayers\Actions $actions) {
                        if ($actions->row->status == GoodsCart::STATUS_ZERO) {
                            $actions->append(
                                '<a title="下单" style="cursor: pointer"  href="javascript:void(0)" class="place"
                                    data-id = "' . $actions->row->id . '" data-user = "' . $actions->row->user_id . '"
                                    data-goods = "' . $actions->row->goods_id . '" data-num = "' . $actions->row->num . '"
                                    data-amount = "' . $actions->row->amount . '"
                                ><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;下单</a>'
                            );
                        }
                    }
                );
            }
        );
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
        return Show::make(
            $id,
            new GoodsCart(),
            function (Show $show) {
                $show->field('id');
                $show->field('goods_id')->as(
                    function ($value) {
                        $ids = explode(',',$value);
                        return GoodsInfo::whereIn('id',$ids)->pluck('name');
                    }
                )->label('blue2');
                $show->field('num');
                $show->field('amount');
                $show->field('user_id')->as(
                    function ($id) {
                        return GoodsUser::whereId($id)->pluck('username');
                    }
                )->label('blue');
                $show->field('numbering');
                $show->field('created_at');
                $show->field('updated_at');

                $show->disableDeleteButton();
                $show->disableEditButton();
            }
        );
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(
            new GoodsCart(),
            function (Form $form) {
                $form->display('id');
                if ($form->isCreating()) {
                    $form->select('user_id')->options(
                        function () {
                            return GoodsUser::all()->pluck('username', 'id');
                        }
                    );
                }
                if ($form->isEditing()) {
                    $name = GoodsUser::where('id', $form->model()->user_id)->value('username');
                    $form->text('user_name')->value($name)->readOnly();
                }
                $form->multipleSelect('goods_id')->options(
                    function () {
                        return GoodsInfo::all()->pluck('name', 'id');
                    }
                )->saving(
                    function ($value) {
                        return implode(',', $value);
                    }
                );
                $form->text('num')->help('输入的数量请与上面的商品名称对应，多个商品间使用 , 分隔');
                $form->hidden('amount');
                $form->hidden('numbering')->value('');
                $form->select('status')->options(admin_trans('goods-cart.options.status'));

                $form->display('created_at');
                $form->display('updated_at');

                $form->saving(
                    function (Form $form) {
                        if ($form->isEditing()) {
                            $form->deleteInput('user_name');
                        }
                        $id = $form->goods_id;
                        $num = explode(',', $form->num);
                        //取出对应商品的价格
                        foreach ($id as $item) {
                            if ($item != null) {
                                $amount[] = GoodsInfo::whereId($item)->value('amount');
                            }
                        }
                        //总价格= 商品价格*对应商品数量 的值相加
                        $count = 0;
                        foreach ($amount as $key => $value) {
                            $sum = bcmul($amount[$key], $num[$key]);
                            $count += $sum;
                        }
                        $form->amount = $count;
                    }
                );
                $form->saved(
                    function (Form $form) {
                        return redirect(admin_url('/goods-cart'));
                    }
                );

                //去除顶部按钮
                $form->disableDeleteButton();
                $form->disableViewButton();
                //去除底部按钮
                $form->footer(
                    function ($footer) {
                        $footer->disableViewCheck();
                        $footer->disableCreatingCheck();
                        $footer->disableEditingCheck();
                    }
                );
            }
        );
    }

    //下单
    public function placeOrder(Request $request)
    {
        if (empty($request->id)) {
            $data = [
                'code' => 'error',
                'msg' => '参数错误'
            ];
            return $data;
        }
        $numbering = time() . rand(0, 9999);
        try {
            DB::beginTransaction();

            $order = new GoodsOrder();
            $order->user_id = $request->user_id;
            $order->goods_id = $request->goods_id;
            $order->num = $request->num;
            $order->amount = $request->amount;
            $order->numbering = $numbering;
            $order->save();

            $res = [
                'numbering' => $numbering,
                'status' => GoodsCart::STATUS_ONE
            ];
            GoodsCart::whereId($request->id)->update($res);

            DB::commit();

            $data = [
                'code' => 'success',
                'msg' => '下单成功'
            ];
            return $data;
        } catch (\Exception $e) {
            DB::rollBack();

            $data = [
                'code' => 'error',
                'msg' => '下单失败'
            ];
            return $data;
        }
    }
}

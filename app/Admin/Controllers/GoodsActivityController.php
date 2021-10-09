<?php

namespace App\Admin\Controllers;

use App\Jobs\Activity;
use App\Models\GoodsActivity;
use App\Models\GoodsInfo;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;
use Illuminate\Http\Request;

class GoodsActivityController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(
            new GoodsActivity(),
            function (Grid $grid) {
                $grid->column('name');
                $grid->column('goods_id')->display(
                    function () {
                        $goods_id = explode(',', $this->goods_id);
                        return GoodsInfo::whereIn('id', $goods_id)->pluck('name');
                    }
                )->label('blue');
                $grid->type->using(admin_trans('goods-activity.options.type'))->label('primary');
                $grid->discount;
                $grid->combine('秒杀', ['money', 'count']);
                $grid->money;
                $grid->count
                    ->if(
                        function ($column) {
                            return $column->getValue() > 0;
                        }
                    )
                    ->then(
                        function () {
                            return $this->count;
                        }
                    )
                    ->else(
                        function (Grid\Column $column) {
                            $column->emptyString();
                        }
                    );
                $grid->column('status')->using(admin_trans('goods-activity.options.status'))
                    ->label(
                        [
                            'default' => 'primary',
                            0 => 'success',
                            1 => 'danger'
                        ]
                    );
                $grid->column('state')->using(admin_trans('goods-activity.options.state'))
                    ->label(
                        [
                            'default' => 'primary',
                            0 => 'blue2',
                            1 => 'success',
                            2 => 'danger'
                        ]
                    );;
                $grid->column('start');
                $grid->column('end');
                $grid->column('description');
                $grid->column('created_at');

                $grid->disableRowSelector();
                $grid->filter(
                    function (Grid\Filter $filter) {
                        $filter->panel();
                        $filter->like('name')->width(3);
                        $filter->equal('type')->select(admin_trans('goods-activity.options.type'))->width(3);
                        $filter->equal('state')->select(admin_trans('goods-activity.options.state'))->width(3);
                        $filter->equal('status')->select(admin_trans('goods-activity.options.status'))->width(3);
                        $filter->between('start')->datetime()->width(4);
                        $filter->between('end')->datetime()->width(4);
                        $filter->between('created_at')->datetime()->width(4);
                    }
                );

                $grid->actions(
                    function (Grid\Displayers\Actions $actions) {
                        $actions->append(
                            '<a title="执行" style="cursor: pointer" href="' . admin_url(
                                "/goods-activity/state/{$actions->row->id}"
                            ) . '"><i class="feather  icon-play grid-action-icon"></i>&nbsp;执行</a>'
                        );
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
            new GoodsActivity(),
            function (Show $show) {
                $show->field('id');
                $show->field('name');
                $show->field('goods_id')->as(
                    function ($value) {
                        $ids = explode(',', $value);
                        return GoodsInfo::whereIn('id', $ids)->pluck('name');
                    }
                )->label('blue2');
                $show->field('status')->using(admin_trans('goods-activity.options.status'))->label();
                $show->field('start');
                $show->field('end');
                $show->field('description');
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
            new GoodsActivity(),
            function (Form $form) {
                $form->display('id');
                $form->text('name')->required();
                $form->multipleSelect('goods_id')->options(
                    function () {
                        return GoodsInfo::all()->pluck('name', 'id');
                    }
                )->saving(
                    function ($value) {
                        return implode(',', $value);
                    }
                );
                $form->radio('type')->options(admin_trans('goods-activity.options.type'))->default(0)
                    ->when(
                        1,
                        function (Form $form) {
                            $form->rate('discount');
                        }
                    )
                    ->when(
                        2,
                        function (Form $form) {
                            $form->text('money');
                            $form->number('count')->default(1);
                        }
                    );
                $form->radio('state')->options(admin_trans('goods-activity.options.state'))->default(0);
                $form->radio('status')->options(admin_trans('goods-activity.options.status'))->default(0);
                $form->datetime('start');
                $form->datetime('end');
                $form->textarea('description')->rows(5);
                $form->display('created_at');
                $form->display('updated_at');

                $form->saving(
                    function (Form $form) {
                        if ($form->type == 2) {
                        } else {
                            $form->count = 0;
                        }
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

    public function state($id)
    {
        $activity_id = $id;
        dispatch(new Activity($activity_id));
    }
}

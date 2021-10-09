<?php

namespace App\Admin\Controllers;

use App\Admin\Renderable\GoodsReturnTable;
use App\Models\GoodsInfo;
use App\Models\GoodsReturn;
use App\Models\GoodsUser;
use Dcat\Admin\Actions\Action;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Http\Request;

class GoodsReturnController extends AdminController
{

    public function __construct(Request $request)
    {
        Admin::style(
            <<<CSS
                .nav-tabs {
                    background-color: #ffffff;
                    margin-top: 10px;
                }
CSS

        );
        $this->status = $request->status;
        return $this;
    }
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new GoodsReturn(), function (Grid $grid) {

            $grid->header(function () {
                $tab = Tab::make();

                $tab->addLink('待审核', '?status=0', $this->status == 0 ? true : false);
                $tab->addLink('已拒绝', '?status=1', $this->status == 1 ? true : false);
                $tab->addLink('已同意', '?status=2', $this->status == 2 ? true : false);
                return $tab;
            });

            if ($this->status == 0) {
                $grid->model()->where('status', 0);
            } elseif ($this->status == 1) {
                $grid->model()->where('status', 1);
            } elseif ($this->status == 2) {
                $grid->model()->where('status', 2);
            }


            $grid->column('user_id')->display(
                function () {
                    return GoodsUser::whereId($this->user_id)->value('username');
                }
            );
            $grid->column('goods_id')->display(
                function () {
                    $goods_ids = explode(',', $this->goods_id);
                    return GoodsInfo::whereIn('id', $goods_ids)->pluck('name');
                }
            )->label('blue2');
            $grid->column('numbering');
            $grid->column('amount');
            $grid->column('status')->select(admin_trans('goods-return.options.status'),true);
            $grid->column('reason');
            $grid->column('created_at','申请时间');
            $grid->column('more', '详情')->display('查看')
                ->modal(function ($modal) {
                    $username = GoodsUser::whereId($this->user_id)->value('username');
                    $modal->title($username . '- 退货详情');

                    return GoodsReturnTable::make(['title' => $this->title]);
                });

            $grid->disableCreateButton();
            $grid->disableViewButton();
            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal('user_id')->select(GoodsUser::all()
                                                      ->pluck('username','id'))->width(3);
                $filter->like('numbering')->width(3);
                $filter->between('created_at','申请时间')->datetime()->width(6);
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
        return Show::make($id, new GoodsReturn(), function (Show $show) {
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
            $show->field('numbering');
            $show->field('amount');
            $show->field('reason');
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
        return Form::make(new GoodsReturn(), function (Form $form) {
            $form->hidden('user_id')->value($form->model()->user_id);
            $user_name = GoodsUser::whereId($form->model()->user_id)->value('username');
            $form->text('user_name', '所属人员')->disable()->value($user_name);

            $form->multipleSelect('goods_id')->options(
                function () {
                    $goods_id = explode(',', $this->goods_id);
                    return GoodsInfo::whereIn('id', $goods_id)->pluck('name', 'id');
                }
            )->saving(
                function ($value) {
                    return implode(',', $value);
                }
            );

            $form->text('numbering')->readOnly();
            $form->text('amount');
            $form->select('status')->options(admin_trans('goods-return.options.status'));
            $form->textarea('reason')->rows(5);

            $form->display('created_at');
            $form->display('updated_at');

            $form->saving(
                function (Form $form) {
                    $form->deleteInput('user_name');
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
}

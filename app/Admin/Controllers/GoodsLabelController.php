<?php

namespace App\Admin\Controllers;

use App\Models\GoodsLabel;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class GoodsLabelController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new GoodsLabel(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal('id')->width(3);
                $filter->like('name')->width(3);
                $filter->between('created_at')->width(6);
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
        return Show::make($id, new GoodsLabel(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('description');
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
        return Form::make(new GoodsLabel(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->textarea('description');

            $form->display('created_at');
            $form->display('updated_at');

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

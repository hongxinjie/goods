<?php

namespace App\Admin\Controllers;

use App\Models\GoodsMenu;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class GoodsMenuController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new GoodsMenu(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('title')->tree();
            $grid->column('parent_id')->display(
                function () {
                    if (empty($this->parent_id)) {
                        return '顶级';
                    }
                    return GoodsMenu::whereId($this->parent_id)->value('title');
                }
            )->label('blue2');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->disableFilter();
//            $grid->filter(function (Grid\Filter $filter) {
//                $filter->panel();
//                $filter->equal('id')->width(4);
//                $filter->like('title')->width(4);
//                $filter->equal('parent_id')->select(
//                    function () {
//                        return GoodsMenu::all()->pluck('title');
//                    }
//                )->width(4);
//                $filter->between('created_at')->datetime()->width(8);
//            });
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
        return Show::make($id, new GoodsMenu(), function (Show $show) {
            $show->field('id');
            $show->field('title');
            $show->field('parent_id')->as(
                function ($parent_id) {
                    if ($parent_id == 0) {
                        return '顶级';
                    }
                    return GoodsMenu::whereId($parent_id)->value('title');
                }
            )->label('blue');
            $show->field('description');
            $show->field('created_at');
            $show->field('updated_at');

            $show->disableEditButton();
            $show->disableDeleteButton();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new GoodsMenu(), function (Form $form) {
            $form->display('id');
            $form->text('title')->required();
            $form->select('parent_id')->options(function (){
                return GoodsMenu::all()->pluck('title','id');
            })->help('不选则默认为顶级');
            $form->textarea('description')->rows(5);

            $form->display('created_at');
            $form->display('updated_at');

//            $form->saving(function (Form $form) {
//                if (isset($form->parent_id) && $form->parent_id == 0) {
//                    $form->parent_id = 0;
//                }
//            });
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

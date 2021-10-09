<?php

namespace App\Admin\Controllers;

use App\Models\Area;
use App\Models\City;
use App\Models\GoodsInfo;
use App\Models\GoodsMenu;
use App\Models\GoodsUser;
use App\Models\Province;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;
use Illuminate\Http\Request;

class GoodsUserController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new GoodsUser(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('username');
            $grid->column('address');
            $grid->column('phone');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

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
        return Show::make($id, new GoodsUser(), function (Show $show) {
            $show->field('id');
            $show->field('username');
            $show->field('address');
            $show->field('phone');
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
        return Form::make(new GoodsUser(), function (Form $form) {
            $form->row(function ($form) {
//                $form->width(4)->display('id');
                $form->width(6)->text('username');
                $form->width(6)->password('password');

                $form->width(4)->select('province','省')->options(Province::all()->pluck('province', 'provinceid'))
                    ->required()->load('city', 'api/city');
                $form->width(4)->select('city', '市')->load('area', 'api/area');
                $form->width(4)->select('area','区/县');
                $form->width(8)->text('detailed','详细地址');
                $form->width(4)->text('phone');

                $form->width(4)->display('created_at');
                $form->width(4)->display('updated_at');
                $form->hidden('address');
            });

            $form->saving(function (Form $form) {
                $province = Province::where('provinceid', $form->province)->value('province');
                $form->address = $province . $form->city . $form->area .$form->detailed;
                $form->deleteInput('province');
                $form->deleteInput('city');
                $form->deleteInput('area');
                $form->deleteInput('detailed');
            });
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

    public function city(Request $request)
    {
        $id = $request->q;
        return City::where('provinceid', $id)->pluck('city' , 'cityid');
    }

    public function area(Request $request)
    {
        $id = $request->q;
        $cityid = City::where('city', $id)->value('cityid');
        return Area::where('cityid', $cityid)->pluck('area', 'id');
    }
}

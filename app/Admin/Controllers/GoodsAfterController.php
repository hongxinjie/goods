<?php

namespace App\Admin\Controllers;

use App\Jobs\Activity;
use App\Models\GoodsAfter;
use App\Models\Province;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoodsAfterController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(
            new GoodsAfter(),
            function (Grid $grid) {
                $grid->fixColumns(1, 0);
                $grid->setActionClass(Grid\Displayers\DropdownActions::class);
                $grid->column('name');
                $grid->column('code');
                $grid->column('phone');
                $grid->column('address');
                $grid->file_path->display(
                    function ($path) {
                        $paths = explode(',', $path);
                        return $paths;
                    }
                )->image(env('APP_URL') . '/uploads', 50, 50);
                $grid->introduce;
                $grid->column('created_at');

                $grid->disableRowSelector();
                $grid->filter(
                    function (Grid\Filter $filter) {
                        $filter->equal('id');
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
            new GoodsAfter(),
            function (Show $show) {
                $show->field('id');
                $show->field('name');
                $show->field('code');
                $show->field('phone');
                $show->field('address');
                $show->field('file_path')
                    ->as(
                        function ($path) {
                            $paths = explode(',', $path);
                            return $paths;
                        }
                    )
                    ->image(env('APP_URL') . '/uploads', 50, 50);
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
            new GoodsAfter(),
            function (Form $form) {
                if ($form->isCreating()){
                    $form->action('/store');
                    $form->multipleSteps()
                        ->add('????????????', function (Form\StepForm $stepForm) {
                            $stepForm->text('name');
                            $stepForm->text('code');
                            $stepForm->text('phone');
                        })
                        ->add('??????', function (Form\StepForm $stepForm) {
                            $stepForm->select('province','???')
                                ->options(Province::all()->pluck('province', 'provinceid'))
                                ->required()->load('city', 'api/city');
                            $stepForm->select('city', '???')
                                ->load('area', 'api/area');
                            $stepForm->select('area','???/???');
                            $stepForm->text('detailed','????????????');
                        })
                        ->add('????????????', function (Form\StepForm $stepForm)  use ($form) {
                            $province = Province::where('provinceid', request()->province)->value('province');
                            $address = $province . request()->city . request()->area . request()->detailed;
                            $stepForm->hidden('address')
                                ->saving(
                                    function () use ($address) {
                                        return $address;
                                    }
                                );
                            $stepForm->multipleImage('file_path')
                                ->required()
                                ->accept('jpg,png,jpeg')
                                ->limit(3)
                                ->help('????????????jpg,png,jpeg??????,???????????????3?????????')
                                ->autoUpload()
                                ->saving(
                                    function ($file_path) {
                                        return implode(',', $file_path);
                                    }
                                );
                            $stepForm->textarea('introduce')->rows(5);
                            $form->deleteInput('province');
                            $form->deleteInput('city');
                            $form->deleteInput('area');
                            $form->deleteInput('detailed');
                        })
                        ->done(function () use ($form) {
                            $resource = $form->getResource(0);

                            $data = [
                                'title'       => '????????????',
                                'description' => '????????????????????????????????????',
                                'createUrl'   => $resource,
                                'backUrl'     => admin_url('goods-after'),
                            ];

                            return view('admin::form.done-step', $data);
                        });
                }
                if ($form->isEditing()) {
                    $form->display('id');
                    $form->text('name');
                    $form->text('code');
                    $form->text('phone');
                    $form->text('address');
                    $form->multipleImage('file_path')
                        ->required()
                        ->accept('jpg,png,jpeg')
                        ->limit(3)
                        ->help('????????????jpg,png,jpeg??????,???????????????3?????????')
                        ->autoUpload()
                        ->saving(
                            function ($file_path) {
                                return implode(',', $file_path);
                            }
                        );
//            $form->markdown('introduce')->disk('admin')->height(600);
                    $form->textarea('introduce')->rows(5);
                    $form->display('created_at');
                    $form->display('updated_at');
                }
                if ($form->isEditing()) {
                    $form->saved(
                        function (Form $form) {
                            $form->success('????????????', admin_url('goods-after'));
                        }
                    );
                }
                //??????????????????
                $form->disableDeleteButton();
                $form->disableViewButton();
                //??????????????????
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
//    public function store()
//    {
//        dd($this->form()->name);
//    }
}

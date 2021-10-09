<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\SwitchGridView;
use App\Admin\Renderable\GoodsInfoTable;
use App\Models\GoodsInfo;
use App\Models\GoodsLabel;
use App\Models\GoodsMenu;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\EasyExcel\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoodsInfoController extends AdminController
{

    public function index(Content $content)
    {
        return $content
            ->title('商品列表')
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
        return Grid::make(new GoodsInfo(), function (Grid $grid) {
            if (request()->get('_view_') !== 'list') {
                // 设置自定义视图
                $grid->view('admin.good-info.image');
                $grid->setActionClass(Grid\Displayers\Actions::class);
            }
            $grid->selector(function (Grid\Tools\Selector $selector) {
                $selector->select('amount', '价格', [
                    '0-19','20-39','40-59','60-79','80-99'
                ],function ($query, $value) {
                    $between = [
                        [0, 19],
                        [20, 39],
                        [40, 59],
                        [60, 79],
                        [80, 99],
                    ];
                    $value = current($value);
                    $query->whereBetween('amount', $between[$value]);
                });

            });
            $grid->column('id');
            $grid->column('name');
            $grid->column('menu_id')->display(
                function () {
                    return GoodsMenu::whereId($this->menu_id)->value('title');
                }
            )->label('blue');
            $grid->column('label_id')->display(
                function () {
                    $label_id = explode(',', $this->label_id);
                    return GoodsLabel::whereIn('id', $label_id)->pluck('name');
                }
            )->label(['default' => 'blue2']);
            $grid->column('num');
            $grid->column('amount');
            $grid->column('file_path','商品图片')->image();
            $grid->column('status')->using(admin_trans('goods-info.options.status'))
                ->label([0 => 'danger',1 => 'success']);
            $grid->column('created_at');

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->equal('id')->width(3);
                $filter->like('name')->width(3);
                $filter->equal('menu_id')->select(
                    function () {
                        return GoodsMenu::where('parent_id','>', 0)->pluck('title','id');
                    }
                )->width(3);
                $filter->equal('status')->select(admin_trans('goods-info.options.status'))->width(3);
                $filter->between('num')->width(6);
                $filter->between('amount')->width(6);
                $filter->between('created_at')->datetime()->width(6);
            });

            //自定义工具
            $grid->tools([new SwitchGridView()]);
            $grid->tools('<a class="btn btn-primary" href="'.admin_url('goods-info/upload').'">
                                    <i class="fa fa-calendar-plus-o"></i>&nbsp;商品导入
                                </a>');
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
        return Show::make($id, new GoodsInfo(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('menu_id')->as(
                function () {
                    return GoodsMenu::whereId($this->menu_id)->value('title');
                }
            )->label('blue');
            $show->field('label_id')->as(
                function () {
                    $label_id = explode(',',$this->label_id);
                    return GoodsLabel::whereIn('id', $label_id)->pluck('name');
                }
            )->label('blue');
            $show->field('num');
            $show->field('amount');
            $show->field('detail');
            $show->field('status')->using(admin_trans('goods-info.options.status'))->label();
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
        return Form::make(new GoodsInfo(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->select('menu_id')->options(
                function () {
                    return GoodsMenu::all()->pluck('title','id');
                }
            );
            $form->multipleSelect('label_id')->options(
                function () {
                    return GoodsLabel::all()->pluck('name','id');
                }
            )->saving(
                function ($value) {
                    return implode(',', $value);
                }
            );
            $form->text('num')->required()->rules('numeric|min:1',
                                                  ['numeric'=> '请填写正确的商品数量','min' => '商品数量不能低于1个']);
            $form->text('amount')->required()->rules('numeric|min:0',
                                                     ['numeric'=> '请填写正确的商品价格格式','min' => '商品价格不能低于0元']);
            $form->textarea('detail')->rows(5);
            $form->image('file_path','商品图片')
                ->autoUpload()          //自动上传
                ->uniqueName()          //生成不重复的名字
                ->disableRemove()       //禁止页面删除文件（替换上传）
                ->saveFullUrl()         //保存域名
                ->required();
            $form->radio('status')->options(admin_trans('goods-info.options.status'))->default(1);

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


    public function upload(Content $content)
    {
        return $content
            ->title($this->title())
            ->description('商品导入')
            ->body($this->uploadForm());

    }

    /*
 * 商品导入表单
 */
    public function uploadForm()
    {
        return Form::make(
            new GoodsInfo(),
            function (Form $form) {

                $form->file('file_path', '商品信息')
                    ->help('文件支持格式xlsx,大小不超过5M')
                    ->accept('xlsx')
                    ->maxSize(5120)
                    ->uniqueName()
                    ->autoUpload()
                    ->required();

                $form->html("<a href='" . admin_url('goods-info/download') . "'>下载模板</a>");

//                $form->saved(
//                    function (Form $form) {
//
//                    }
//                );

                //去除顶部按钮
                $form->disableDeleteButton();
                $form->disableViewButton();
                //去除底部按钮
                $form->footer(function ($footer) {
                    $footer->disableViewCheck();
                    $footer->disableCreatingCheck();
                    $footer->disableEditingCheck();
                });
            }
        );
    }

    /*
     * 导入模板下载
     */
    public function download()
    {
//        return response()->download(public_path().'/excel/goods.xlsx','商品信息'.'.xlsx');
        return Storage::download(public_path().'/excel/goods.xlsx','商品信息'.'.xlsx');
    }
}

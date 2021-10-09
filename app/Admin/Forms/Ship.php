<?php

namespace App\Admin\Forms;

use App\Models\Company;
use App\Models\GoodsCourier;
use App\Models\GoodsOrder;
use App\Models\ResumeApplicationRecord;
use App\xinlpay\controller\XinlPas;
use Dcat\Admin\Widgets\Form;
use Illuminate\Http\Request;
use PhpParser\Lexer\TokenEmulator\FlexibleDocStringEmulator;
use Symfony\Component\HttpFoundation\Response;

use function foo\func;


class Ship extends Form
{
    protected $id;

    public function __construct($data = null)
    {
        $this->id = $data['id'];
        parent::__construct();
    }
    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return Response
     */
    public function handle(array $input)
    {
        $id = $input['id'] ?? null;
        $courier_id = $input['courier_id'] ?? null;
        $courier_num = $input['courier_num'] ?? null;
        $time = $input['time'] ?? null;

        if (!$id || !$courier_id || !$courier_num || !$time) {
            return $this->error('参数错误');
        }
        $data = GoodsOrder::find($id);
        if ($data) {
            $data->courier_id = $input['courier_id'];
            $data->courier_num = $input['courier_num'];
            $data->time = $input['time'];
            $data->status = GoodsOrder::STATUS_THEN;
            $data->save();

            return $this->success('发货成功');
        }
        return $this->error('发货失败');
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->hidden('id')->value($this->id);
        $this->select('courier_id')->options(
            function () {
                return GoodsCourier::all()->pluck('name','id');
            }
        );
        $this->text('courier_num');
        $this->datetime('time');
    }


}

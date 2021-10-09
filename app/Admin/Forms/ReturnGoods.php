<?php

namespace App\Admin\Forms;

use App\Models\Company;
use App\Models\GoodsOrder;
use App\Models\GoodsReturn;
use App\Models\GoodsUser;
use App\Models\ResumeApplicationRecord;
use App\xinlpay\controller\XinlPas;
use Dcat\Admin\Widgets\Form;
use Illuminate\Http\Request;
use PhpParser\Lexer\TokenEmulator\FlexibleDocStringEmulator;
use Symfony\Component\HttpFoundation\Response;

use function foo\func;


class ReturnGoods extends Form
{
    protected $id;
    protected $user_id;
    protected $goods_id;
    protected $numbering;
    protected $amount;
    protected $num;

    public function __construct($data = null)
    {
        $this->id = $data['id'];
        $this->user_id = $data['user_id'];
        $this->goods_id = $data['goods_id'];
        $this->numbering = $data['numbering'];
        $this->amount = $data['amount'];
        $this->num = $data['num'];

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
        if (!empty($input['id']) || !empty($input['goods_id']) || !empty($input['numbering']) || !empty($input['user_id']) || !empty($input['amount'])) {
            $res = GoodsOrder::find($input['id']);
            $res->status = GoodsOrder::STATUS_APPLY;
            $res->save();
            if ($res->save() == true){
                $data = new GoodsReturn();
                $data->user_id = $input['user_id'];
                $data->goods_id = $input['goods_id'];
                $data->numbering = $input['numbering'];
                $data->amount = $input['amount'];
                $data->reason = $input['reason'];
                $data->num = $input['num'];
                $data->save();
                return $this->success('退货申请已提交');
            } else {
                return $this->error('退货申请提交失败');
            }
        }
        return $this->error('参数错误');
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->hidden('id')->value($this->id);
        $this->hidden('goods_id')->value($this->goods_id);
        $this->hidden('numbering')->value($this->numbering);
        $this->hidden('amount')->value($this->amount);
        $this->hidden('user_id')->value($this->user_id);
        $this->hidden('num')->value($this->num);

        $this->text('user_name','所属用户')->value(GoodsUser::whereId($this->user_id)->value('username'));
        $this->textarea('reason', '退货理由')->rows(5);
    }


}

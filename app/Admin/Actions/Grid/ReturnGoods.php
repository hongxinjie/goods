<?php

namespace App\Admin\Actions\Grid;

use App\Models\GoodsUser;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Grid\Model;
use App\Admin\Forms\ReturnGoods as ReturnGoodsForm;
use Dcat\Admin\Traits\HasPermissions;
use Illuminate\Contracts\Auth\Authenticatable;

class ReturnGoods extends RowAction
{
    public function render()
    {
        $id = "mail-invoice-return-{$this->getKey()}";

        // 模态窗
        $this->modal($id);

        return <<<HTML
<span class="grid-expand" data-toggle="modal" data-target="#{$id}">
   <a href="javascript:void(0)"><i class="fa fa-undo"></i>&nbsp;退货申请</a>
</span>
HTML;
    }

    protected function modal($id)
    {
        $data = [
            'id' => $this->getKey(),
            'user_id' => $this->row->user_id,
            'goods_id' => $this->row->goods_id,
            'numbering' => $this->row->numbering,
            'amount' => $this->row->amount,
            'num' => $this->row->num
        ];
        // 工具表单
        $form = new ReturnGoodsForm($data);

        // 在弹窗标题处显示当前行的用户名
        $username = GoodsUser::where('id', $this->row->user_id)->value('username');

        // 通过 Admin::html 方法设置模态窗HTML
        Admin::html(
            <<<HTML
<div class="modal fade" id="{$id}">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">退货申请 - {$username}</h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        {$form->render()}
      </div>
    </div>
  </div>
</div>
HTML
        );
    }

    /**
     * @return string|array|void
     */
    public function confirm()
    {
        // return ['Confirm?', 'contents'];
    }

    /**
     * @param Model|Authenticatable|HasPermissions|null $user
     *
     * @return bool
     */
    protected function authorize($user): bool
    {
        return true;
    }

    /**
     * @return array
     */
    protected function parameters()
    {
        return [];
    }
}

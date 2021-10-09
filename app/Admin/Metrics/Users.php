<?php

namespace App\Admin\Metrics;

use App\Models\GoodsUser;
use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;

class Users extends Line
{
    /**
     * 初始化卡片内容
     *
     * @return void
     */
    protected function init()
    {
        parent::init();

        $this->title('新增用户');
        $this->subTitle('最近30天');
    }

    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return mixed|void
     */
    public function handle(Request $request)
    {
        $res = $this->getUserNum(10,30);
        // 卡片内容
        $this->withContent($res['count']);
        // 图表数据
        $this->withChart($res['list'])->chartStraight();

        $this->chartHeight(140);
    }

    /**
     * 统计新增用户数
     *
     * @param $len 长度
     * @param $days  天数
     *
     * @return array
     */
    public function getUserNum($len, $days)
    {
        $end_time = strtotime(date('Y-m-d'));
        $start_time = date('Y-m-d H:i:s', $end_time - (86400 * $days));
        $count = GoodsUser::where(
            [
                ['created_at', '>=', $start_time],
                ['created_at', '<', date('Y-m-d H:i:s', $end_time)]
            ]
        )->count();

        $plen = round($days / $len);
        $list = [];
        for ($i = $len; $i > 0; $i--) {
            $create_start = date('Y-m-d H:i:s', $end_time - (86400 * $i * $plen));
            $create_end = date('Y-m-d H:i:s', $end_time - (86400 * ($i-1) * $plen));
            $total = GoodsUser::where(
                [
                    ['created_at', '>=', $create_start],
                    ['created_at', '<', $create_end]
                ]
            )->count();
            $list[] = $total;
        }
        return ['count' => $count , 'list' => $list];
    }

    /**
     * 设置图表数据.
     *
     * @param array $data
     *
     * @return $this
     */
    public function withChart(array $data)
    {
        return $this->chart([
            'series' => [
                [
                    'name' => $this->title,
                    'data' => $data,
                ],
            ],
        ]);
    }

    /**
     * 设置卡片内容.
     *
     * @param string $content
     *
     * @return $this
     */
    public function withContent($content)
    {
        return $this->content(
            <<<HTML
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
    <h2 class="ml-1 font-lg-1">{$content}</h2>
    <span class="mb-0 mr-1 text-80">{$this->title}</span>
</div>
HTML
        );
    }
}

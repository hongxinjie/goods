<?php

namespace App\Admin\Metrics;

use App\Models\GoodsOrder;
use Dcat\Admin\Widgets\Metrics\Round;
use Illuminate\Http\Request;

class Order extends Round
{
    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $this->title('订单详情');
        $this->chartLabels(['已发货', '未发货', '申请退货']);
        $this->dropdown([
            '7' => '最近7天',
            '15' => '最近15天',
            '30' => '最近30天',
            '365' => '最近365天',
        ]);
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
        switch ($request->get('option')) {
            case '365':
                $res = $this->orderStatistics(365);
                // 卡片内容
                $this->withContent($res['then_count'], $res['wait_count'], $res['apply_count']);

                // 图表数据
                $this->withChart([$res['then_count'], $res['wait_count'], $res['apply_count']]);

                // 总数
                $this->chartTotal('总数', $res['count']);
                break;
            case '30':
                $res = $this->orderStatistics(30);
                // 卡片内容
                $this->withContent($res['then_count'], $res['wait_count'], $res['apply_count']);

                // 图表数据
                $this->withChart([$res['then_count'], $res['wait_count'], $res['apply_count']]);

                // 总数
                $this->chartTotal('总数', $res['count']);
                break;
            case '15':
                $res = $this->orderStatistics(15);
                // 卡片内容
                $this->withContent($res['then_count'], $res['wait_count'], $res['apply_count']);

                // 图表数据
                $this->withChart([$res['then_count'], $res['wait_count'], $res['apply_count']]);

                // 总数
                $this->chartTotal('总数', $res['count']);
                break;
            case '7':
                $res = $this->orderStatistics(7);
                // 卡片内容
                $this->withContent($res['then_count'], $res['wait_count'], $res['apply_count']);

                // 图表数据
                $this->withChart([$res['then_count'], $res['wait_count'], $res['apply_count']]);

                // 总数
                $this->chartTotal('总数', $res['count']);
                break;
            default:

                $res = $this->orderStatistics(7);
                // 卡片内容
                $this->withContent($res['then_count'], $res['wait_count'], $res['apply_count']);

                // 图表数据
                $this->withChart([$res['then_count'], $res['wait_count'], $res['apply_count']]);

                // 总数
                $this->chartTotal('总数', $res['count']);
        }
    }

    /**
     * 统计数量
     *
     * @param $days 天数
     *
     * @return array
     *
     */
    public function orderStatistics($days)
    {
        $end_time = strtotime(date('Y-m-d'));
        $start_time = date('Y-m-d H:i:s', $end_time - (86400 * $days));
        $count = GoodsOrder::where(
            [
                ['created_at', '>=', $start_time],
                ['created_at', '<', date('Y-m-d H:i:s', $end_time)]
            ]
        )->count();
        //待发货
        $wait_count = GoodsOrder::where(
            [
                ['created_at', '>=', $start_time],
                ['created_at', '<', date('Y-m-d H:i:s', $end_time)],
                ['status',0]
            ]
        )->count();
        //已发货
        $then_count = GoodsOrder::where(
            [
                ['created_at', '>=', $start_time],
                ['created_at', '<', date('Y-m-d H:i:s', $end_time)],
                ['status',1]
            ]
        )->count();
        //申请退货
        $apply_count = GoodsOrder::where(
            [
                ['created_at', '>=', $start_time],
                ['created_at', '<', date('Y-m-d H:i:s', $end_time)],
                ['status',3]
            ]
        )->count();

        return [
            'count' => $count,
            'wait_count' => $wait_count,
            'then_count' => $then_count,
            'apply_count'=> $apply_count
        ];
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
            'series' => $data,
        ]);
    }

    /**
     * 卡片内容.
     *
     * @param int $finished
     * @param int $pending
     * @param int $rejected
     *
     * @return $this
     */
    public function withContent($finished, $pending, $rejected)
    {
        return $this->content(
            <<<HTML
<div class="col-12 d-flex flex-column flex-wrap text-center" style="max-width: 220px">
    <div class="chart-info d-flex justify-content-between mb-1 mt-2" >
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-primary"></i>
              <span class="text-bold-600 ml-50">已发货</span>
          </div>
          <div class="product-result">
              <span>{$finished}</span>
          </div>
    </div>

    <div class="chart-info d-flex justify-content-between mb-1">
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-warning"></i>
              <span class="text-bold-600 ml-50">未发货</span>
          </div>
          <div class="product-result">
              <span>{$pending}</span>
          </div>
    </div>

     <div class="chart-info d-flex justify-content-between mb-1">
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-danger"></i>
              <span class="text-bold-600 ml-50">申请退货</span>
          </div>
          <div class="product-result">
              <span>{$rejected}</span>
          </div>
    </div>
</div>
HTML
        );
    }
}

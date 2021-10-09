<?php

namespace App\Jobs;

use App\Models\GoodsActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Activity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $activity_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($activity_id)
    {
        //
        $this->activity_id = $activity_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $id = $this->activity_id;
        $activity = GoodsActivity::whereId($id)->first();
        $now_time = date('Y-m-d H:i:s',time());
        if ($activity->start > $now_time) {
            $state = GoodsActivity::STATE_ON;
            GoodsActivity::whereId($id)->update(['state' => $state]);
        }
        if ($activity->end < $now_time) {
            $state = GoodsActivity::STATE_END;
            GoodsActivity::whereId($id)->update(['state' => $state]);
        }
    }
}

<?php

namespace App\Observers;

use App\Models\Stock;
use App\Models\Adjustment;
use App\Models\AdjustmentDetail;

class AdjustmentObserver
{
    /**
     * Handle the Adjustment "deleted" event.
     *
     * @param  \App\Models\Adjustment  $adjustment
     * @return void
     */
    public function deleted(Adjustment $adjustment)
    {
        $datas = AdjustmentDetail::where('adjustment_id', $adjustment->id)->get();
        foreach ($datas ?? [] as  $item) {
            if ($item->type == 'Subtraction') {
                Stock::where('product_id',  $item->product_id)->increment('quantity', $item->quantity);
            }
            if ($item->type == 'Addition') {
                Stock::where('product_id',  $item->product_id)->decrement('quantity', $item->quantity);
            }
            $item->delete();
        }
    }
}

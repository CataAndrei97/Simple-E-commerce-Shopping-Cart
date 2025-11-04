<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendDailySalesReport implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $sales = Order::with('items.product')
            ->whereDate('created_at', today())
            ->get();

        Log::info("Daily Sales Report for " . now()->toDateString());
        Log::info($sales->toJson());
    }
}

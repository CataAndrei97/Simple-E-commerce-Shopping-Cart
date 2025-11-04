<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class NotifyLowStock implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly Product $product) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Low stock alert: {$this->product->name} ({$this->product->stock_quantity})");
    }
}

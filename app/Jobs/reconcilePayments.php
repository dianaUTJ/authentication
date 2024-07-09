<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\StripeService;
use Illuminate\Support\Facades\Log;

class reconcilePayments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public function handle(StripeService $stripeService)
    {
        try {
            $stripeService->reconcileMissedPayments();
        } catch (\Exception $e) {
            // Handle the exception
            Log::error('Failed to reconcile missed payments: ' . $e->getMessage());

            // Optionally, you can re-throw the exception if you want the job to be retried
            // throw $e;
        }    }
}

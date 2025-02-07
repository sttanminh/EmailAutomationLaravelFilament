<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Mail\OrderReportMail;
use App\Mail\SendPdfMail;

class ProcessOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderId;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle()
    {
        $order = Order::find($this->orderId);
        if (!$order) {
            Log::error("❌ Order not found: ID {$this->orderId}");
            return;
        }

        Log::info("🔄 Processing Order ID: {$this->orderId}");

        // URL from order
        $url = 'https://www.aami.com.au/policy-documents/personal/comprehensive-car-insurance.html#roadside-assistance-terms-conditions';
        $downloadPath = storage_path('app/public/pdfs');
        // Run Puppeteer script to click logo
        $scriptPath = base_path('puppeteer/exportReport.cjs'); 
        $process = new Process(["node", $scriptPath, $url, $downloadPath]);
        $process->setTimeout(300);
        $filePath = base_path('example.pdf');

        try {
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $customerName = $order->customer->name;
            $orderId = $order->id;

            \Log::info("📨 Sending invoice for Order ID: {$orderId}, Customer: {$customerName}");

            // ✅ Send email with the correct details
            Mail::to('st.tanminh@gmail.com')
                ->send(new SendPdfMail($filePath, $customerName, $orderId));

            \Log::info("✅ Invoice email sent for Order ID: {$orderId}");

        } catch (Exception $e) {
            Log::error("⚠️ Error processing order {$orderId}: " . $e->getMessage());
        }
    }
}

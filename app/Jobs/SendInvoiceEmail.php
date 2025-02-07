<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPdfMail;
use App\Models\Order;

class SendInvoiceEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle()
{
    $filePath = base_path('example.pdf'); // Ensure this file exists

    if (!file_exists($filePath)) {
        \Log::error('PDF file not found: ' . $filePath);
        return;
    }

    // ✅ Load the order with customer data
    $order = Order::with('customer')->find($this->orderId);

    if (!$order || !$order->customer) {
        \Log::error("❌ Order ID {$this->orderId} does not have a valid customer.");
        return;
    }

    $customerName = $order->customer->name;
    $orderId = $order->id;

    \Log::info("📨 Sending invoice for Order ID: {$orderId}, Customer: {$customerName}");

    // ✅ Send email with the correct details
    Mail::to('st.tanminh@gmail.com')
        ->send(new SendPdfMail($filePath, $customerName, $orderId));

    \Log::info("✅ Invoice email sent for Order ID: {$orderId}");
}

}

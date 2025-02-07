<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendPdfMail;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Jobs\SendInvoiceEmail;

class EmailController extends Controller
{
    /**
     * Send email with PDF attached for each order.
     */
    public function sendEmails()
{
    $orders = Order::all(); // Get all orders

    if ($orders->isEmpty()) {
        return response()->json(['message' => 'No orders found to send emails.']);
    }

    foreach ($orders as $order) {
        // ✅ Check if a job for this order already exists in the queue
        if (!\DB::table('jobs')->where('payload', 'like', '%"id":'.$order->id.'%')->exists()) {
            dispatch(new SendInvoiceEmail($order->id));
        } else {
            \Log::warning("⚠️ Job for Order ID {$order->id} is already queued.");
        }
    }

    return response()->json(['message' => count($orders) . ' emails queued for sending.']);
}

}

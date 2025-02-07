<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPdfMail extends Mailable
{
    use Queueable, SerializesModels;

    public $filePath; // Store file path
    public $customerName;
    public $orderId;

    /**
     * Create a new message instance.
     */
    public function __construct($filePath,$customerName,$orderId)
    {
        $this->filePath = $filePath;
        $this->customerName = $customerName;
        $this->orderId = $orderId;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject("Invoice for Order #{$this->orderId} - {$this->customerName}")
                    ->view('emails.pdf_email') // Reference the email template
                    ->attach($this->filePath, [
                        'as' =>  "Invoice-Order-{$this->orderId}.pdf", // Name of the attached file
                        'mime' => 'application/pdf',
                    ])
                    ->with([
                        'customerName' => $this->customerName,
                        'orderId' => $this->orderId,
                    ]);
    }
}

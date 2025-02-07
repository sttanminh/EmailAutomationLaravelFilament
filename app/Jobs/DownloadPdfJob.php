<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\PdfLink;
use Exception;

class DownloadPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public int $pdfLinkId; // Store only the ID, not the model
    // 🔄 Maximum retry attempts
    public int $tries = 3;

    // 🔄 Delay before retrying (5 seconds)
    public int $backoff = 5;

    public function __construct(int $pdfLinkId)
    {
        $this->pdfLinkId = $pdfLinkId;
    }

    public function handle()
    {
        // Fetch the model instance using the ID
        $pdfLink = PdfLink::find($this->pdfLinkId);
        Log::info("✅ Processing PDF Download: " . $pdfLink);
        if (!$pdfLink) {
            Log::error("❌ PDF Link not found for ID: " . $this->pdfLinkId);
            return;
        }

        // Throw an exception for testing
        throw new Exception("Manually fail for testing");

        Log::info("✅ Processing PDF Download: " . $pdfLink->id);
    }
}





    // public function handle()
    // {
    //     Log::info("Processing PDF URL: " . $this->pdfLink->url);

    //     $downloadPath = storage_path('app/public/pdfs');
    //     if (!file_exists($downloadPath)) {
    //         mkdir($downloadPath, 0777, true);
    //     }

    //     // Run Puppeteer script with the URL
    //     $scriptPath = base_path('puppeteer/download-pdf.js');
    //     $process = new Process(["node", $scriptPath, $this->pdfLink->url, $downloadPath]);
    //     $process->setTimeout(300); // 5 minutes
    //     $process->run();
        
    //     if ($process->isSuccessful()) {
    //         Log::info("✅ PDF downloaded successfully: " . $this->pdfLink->url);
    //         $this->pdfLink->update(['processed' => true]);
    //     } else {
    //         Log::error("❌ PDF download failed: " . $process->getErrorOutput());
    //     }
    // }
// }

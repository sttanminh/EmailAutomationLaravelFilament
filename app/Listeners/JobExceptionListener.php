<?php

namespace App\Listeners;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobExceptionListener
{
    public function handle(JobExceptionOccurred $event)
    {
        $payload = json_decode($event->job->getRawBody(), true);
        Log::warning("⚠️ Partial Failure: " . json_encode($payload, JSON_PRETTY_PRINT));

        $uuid = $event->job->uuid();
        Log::warning("⚠️ Event Job UUID: " . $uuid);
        Log::warning("⚠️ Exception: " . $event->exception->getMessage());

        try {
            $jobCommand = unserialize($payload['data']['command']);

            if (isset($jobCommand->pdfLinkId)) {
                $pdfLinkId = $jobCommand->pdfLinkId;
                $attempts = $event->job->attempts(); // Current attempt count
                Log::info("📌 Attempt: " . $attempts);
                Log::info("📌 PDF Link ID: " . $pdfLinkId);
            } else {
                Log::error("⚠️ PDF Link ID missing in job data.");
                $pdfLinkId = null;
            }
        } catch (\Exception $e) {
            Log::error("⚠️ Failed to deserialize job data: " . $e->getMessage());
            $pdfLinkId = null;
        }

        if (!$pdfLinkId) {
            Log::error("⚠️ Still missing PDF Link ID, investigate further.");
        }

        // ✅ Insert each failed attempt into job_failures table
        DB::table('job_failures')->insert([
            'uuid' => $uuid,
            'job_name' => $payload['displayName'] ?? 'Unknown Job',
            'pdf_link_id' => $pdfLinkId,
            'exception' => $event->exception->getMessage(),
            'failed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info("✅ Logged partial failure (attempt {$event->job->attempts()}) for job: {$uuid}");
    }
}

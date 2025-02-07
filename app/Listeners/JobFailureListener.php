<?php

namespace App\Listeners;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobFailureListener
{
    public function handle(JobFailed $event)
    {
        // 🔍 Log full payload for debugging
        $payload = json_decode($event->job->getRawBody(), true);
        Log::error("🚨 Full Payload: " . json_encode($payload, JSON_PRETTY_PRINT));

        // 🔍 Extract Job UUID
        $uuid = $event->job->uuid();
        Log::error("🚨 Event Job UUID: " . $uuid);
        Log::error("🚨 Exception: " . $event->exception->getMessage());

        // ✅ Deserialize job command to extract `pdfLinkId`
        try {
            $jobCommand = unserialize($payload['data']['command']);

            if (isset($jobCommand->pdfLinkId)) {
                $pdfLinkId = $jobCommand->pdfLinkId;
                
                Log::info("✅ Extracted PDF Link ID: " . $pdfLinkId);
            } else {
                Log::error("⚠️ PDF Link ID is missing in the deserialized job data.");
                $pdfLinkId = null;
            }
        } catch (\Exception $e) {
            Log::error("⚠️ Failed to deserialize job command: " . $e->getMessage());
            $pdfLinkId = null;
        }

        if (!$pdfLinkId) {
            Log::error("⚠️ PDF Link ID is still missing! Investigate further.");
        }

        // ✅ Insert failure record into `job_failures` table
        $exceptionMessage = substr($event->exception->getMessage(), 0, 5000);
        DB::table('job_failures')->insert([
            'uuid' => $uuid,
            'job_name' => $payload['displayName'] ?? 'Unknown Job',
            'pdf_link_id' => $pdfLinkId,
            'exception' => $exceptionMessage,
            'failed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info("✅ Stored job failure: {$uuid} in `job_failures` table with PDF Link ID: {$pdfLinkId}");
    }
}

<?php
namespace App\Http\Controllers;

use App\Jobs\DownloadPdfJob;
use App\Models\PdfLink;
use Illuminate\Http\Request;

class PdfDownloadController extends Controller
{
    public function processPdfs()
    {
        $pdfLinks = PdfLink::where('processed', false)->get();

        foreach ($pdfLinks as $pdfLink) {
            dispatch(new DownloadPdfJob($pdfLink->id, 1)); // ✅ Pass only the ID
        }

        return response()->json(['message' => 'PDF downloads queued']);
    }
}

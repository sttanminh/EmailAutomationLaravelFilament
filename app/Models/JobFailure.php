<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFailure extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',         // Add job UUID for retrying
        'job_name',
        'pdf_link_id',
        'exception',
        'failed_at',
    ];

    public function pdfLink()
    {
        return $this->belongsTo(PdfLink::class);
    }
}

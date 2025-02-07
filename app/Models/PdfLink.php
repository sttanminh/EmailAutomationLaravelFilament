<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfLink extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'processed'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicDocument extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'category', 'year', 'file_size', 'description', 'file_path', 'file_url', 'download_count'];
}

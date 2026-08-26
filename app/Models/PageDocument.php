<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}

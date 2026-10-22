<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'summary', 'content', 'image_url', 'link_url', 'category', 'status', 'order', 'published_at', 'views', 'is_featured'
    ];

    protected $casts = [
        'published_at' => 'date',
    ];
}

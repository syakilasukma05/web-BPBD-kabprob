<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'slug', 
        'content', 
        'image_url', 
        'image_path', 
        'image_name', 
        'image_type', 
        'image_size', 
        'is_published'
    ];


    public function documents()
    {
        return $this->hasMany(PageDocument::class)->orderBy('created_at', 'desc');
    }

    public function links()
    {
        return $this->hasMany(PageLink::class)->orderBy('created_at', 'desc');
    }
}


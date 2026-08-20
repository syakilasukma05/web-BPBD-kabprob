<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatedLink extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'image_url', 'url', 'order', 'is_active'];
}

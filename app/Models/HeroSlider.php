<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlider extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'subtitle', 'image_url', 'button_text', 'button_url', 'order', 'is_active'];
}

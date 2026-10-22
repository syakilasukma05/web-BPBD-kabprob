<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlider extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'subtitle', 'image_url', 'button_text', 'button_url', 'order', 'is_active'];

    public function setImageUrlAttribute($value)
    {
        if ($value && preg_match('#(/uploads/sliders/.*)#i', $value, $matches)) {
            $this->attributes['image_url'] = $matches[1];
        } else {
            $this->attributes['image_url'] = $value;
        }
    }

    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (preg_match('#(/uploads/sliders/.*)#i', $value, $matches)) {
            return $matches[1];
        }

        return $value;
    }
}


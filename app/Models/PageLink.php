<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'title',
        'url',
        'description',
        'target',
        'status',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}

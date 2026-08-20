<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavigationMenu extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'url', 'parent_id', 'order', 'target', 'is_active'];

    public function children()
    {
        return $this->hasMany(NavigationMenu::class, 'parent_id')->where('is_active', true)->orderBy('order', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(NavigationMenu::class, 'parent_id');
    }
}

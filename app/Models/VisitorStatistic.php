<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorStatistic extends Model
{
    use HasFactory;

    protected $table = 'visitor_statistics';

    protected $fillable = [
        'date',
        'count',
        'period_type',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'count' => 'integer',
    ];
}

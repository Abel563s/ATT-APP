<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceCode extends Model
{
    protected $fillable = [
        'code',
        'label',
        'description',
        'bg_color',
        'text_color',
        'ring_color',
        'is_active',
    ];

    public function colorClasses(): string
    {
        return "{$this->bg_color} {$this->text_color} {$this->ring_color}";
    }
}

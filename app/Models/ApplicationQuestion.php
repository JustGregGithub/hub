<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_category_id',
        'question',
        'type',
        'options',
        'position'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    const OPTION_TYPES = [
        'Input' => 10,
        'Textarea' => 20,
        'Select' => 30,
        'Radio' => 40
    ];

    public function category() {
        return $this->belongsTo(ApplicationCategory::class);
    }
}

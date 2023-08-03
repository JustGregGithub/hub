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
        'position'
    ];

    public function category() {
        return $this->belongsTo(ApplicationCategory::class);
    }
}

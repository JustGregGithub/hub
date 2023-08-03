<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'colour_left',
        'colour_right',
        'is_default'
    ];

    public function applications() {
        return $this->hasMany(ApplicationCategory::class);
    }

    public function categories() {
        return $this->hasMany(ApplicationCategory::class);
    }
}

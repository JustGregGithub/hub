<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'is_hidden'
    ];

    public function tickets() {
        return $this->hasMany(Ticket::class, 'category_id', 'id');
    }

    public function statusCount($status = null) {
        return $this->tickets()->where('status', $status)->count();
    }

    public static function getRole($category_id) {
        return self::where('id', $category_id)->first()->role;
    }

    public static function getDefault() {
        return self::where('deletable', false)->first();
    }
}

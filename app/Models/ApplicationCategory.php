<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'information',
        'manager_role',
        'worker_role',
        'is_open',
        'create_interview',
        'interview_category',
    ];

    public function applications() {
        return $this->hasMany(Application::class);
    }

    public function questions() {
        return $this->hasMany(ApplicationQuestion::class);
    }

    public static function name($id) {
        return self::where('id', $id)->first()->name ?? 'Unknown';
    }

    public static function information($id) {
        return self::where('id', $id)->first()->information ?? 'Information not available.';
    }

    public function statusCount($status = null) {
        return $this->applications()->where('status', $status)->count();
    }
}

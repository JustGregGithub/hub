<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'application_category_id',
        'questions',
        'content',
        'status',
        'worker_id',
        'reason'
    ];

    protected $casts = [
        'questions' => 'array',
        'content' => 'array'
    ];

    const DEFAULT_STATUS = 'Under Review';

    const STATUSES = [
        'Under Review' => 10,
        'Accepted' => 20,
        'Denied' => 30,
    ];

    public function category() {
        return $this->belongsTo(ApplicationCategory::class, 'application_category_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function statusColor($status) {
        switch ($status) {
            case self::STATUSES['Under Review']:
                return 'orange-500';
            case self::STATUSES['Accepted']:
                return 'green-500';
            case self::STATUSES['Denied']:
                return 'red-500';
            default:
                return 'gray-500';
        }
    }

    public static function statusForeColor($status) {
        switch ($status) {
            case self::STATUSES['Under Review']:
                return 'orange-200';
            case self::STATUSES['Accepted']:
                return 'green-200';
            case self::STATUSES['Denied']:
                return 'red-200';
            default:
                return 'gray-200';
        }
    }

    public static function status($status_id) {
        return array_search($status_id, self::STATUSES);
    }
}

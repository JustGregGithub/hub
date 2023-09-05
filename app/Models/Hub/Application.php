<?php

namespace App\Models\Hub;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Hub\Application
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $application_category_id
 * @property array $questions
 * @property array $content
 * @property string $status
 * @property int|null $worker_id
 * @property string|null $reason
 * @property array|null $ai_statistics
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Hub\ApplicationCategory|null $category
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Application newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Application newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Application query()
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereAiStatistics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereApplicationCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereWorkerId($value)
 * @mixin \Eloquent
 */
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
        'reason',
        'ai_statistics'
    ];

    protected $casts = [
        'questions' => 'array',
        'content' => 'array',
        'ai_statistics' => 'array'
    ];

    const DEFAULT_STATUS = 'Under Review';

    const STATUSES = [
        'Under Review' => 10,
        'Accepted' => 20,
        'Denied' => 30,
    ];

    CONST AI_PERCENTAGES = [
        'None' => 0,
        'Low' => 25,
        'Medium' => 50,
        'High' => 75,
        'Very High' => 100
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

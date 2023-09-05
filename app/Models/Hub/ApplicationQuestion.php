<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Hub\ApplicationQuestion
 *
 * @property int $id
 * @property int $application_category_id
 * @property int $position
 * @property string $question
 * @property int $type
 * @property array|null $options
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Hub\ApplicationCategory|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationQuestion whereApplicationCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationQuestion whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationQuestion wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationQuestion whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

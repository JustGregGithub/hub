<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Hub\ApplicationSection
 *
 * @property int $id
 * @property string $name
 * @property string|null $colour_left
 * @property string|null $colour_right
 * @property int $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hub\ApplicationCategory> $applications
 * @property-read int|null $applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hub\ApplicationCategory> $categories
 * @property-read int|null $categories_count
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationSection query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationSection whereColourLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationSection whereColourRight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationSection whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationSection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationSection whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

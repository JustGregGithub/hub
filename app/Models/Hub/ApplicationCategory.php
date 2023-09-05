<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Hub\ApplicationCategory
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $information
 * @property string $guild
 * @property string|null $role
 * @property string $manager_role
 * @property string $worker_role
 * @property int $is_open
 * @property int $create_interview
 * @property int|null $interview_category
 * @property int $add_role
 * @property string|null $role_guild
 * @property int|null $application_section_id
 * @property int $restrict
 * @property string|null $restrict_guild
 * @property string|null $restrict_role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hub\Application> $applications
 * @property-read int|null $applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hub\ApplicationQuestion> $questions
 * @property-read int|null $questions_count
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereAddRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereApplicationSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereCreateInterview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereGuild($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereInterviewCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereIsOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereManagerRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereRestrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereRestrictGuild($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereRestrictRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereRoleGuild($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationCategory whereWorkerRole($value)
 * @mixin \Eloquent
 */
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
        'restrict',
        'restrict_guild',
        'resitrct_role'
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

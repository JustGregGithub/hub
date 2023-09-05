<?php

namespace App\Models\Staff;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Staff\ServerRole
 *
 * @property string $id
 * @property string $guild
 * @property string $name
 * @property int $priority
 * @property int $can_warn
 * @property int $can_kick
 * @property int $can_ban
 * @property int $can_note
 * @property int $can_commend
 * @property int $can_view_staff
 * @property int $can_manage_record
 * @property int $can_manage_permissions
 * @property int $can_view_timeclock
 * @property int $locked
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereCanBan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereCanCommend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereCanKick($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereCanManagePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereCanManageRecord($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereCanNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereCanViewStaff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereCanViewTimeclock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereCanWarn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereGuild($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerRole whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ServerRole extends Model
{
    use HasFactory;

    protected $table = 'server_roles';
    protected $fillable = [
        'server_id',
        'guild',
        'id',
        'name',
        'priority',
        'can_warn',
        'can_kick',
        'can_ban',
        'can_note',
        'can_commend',
        'can_view_staff',
        'can_manage_record',
        'can_manage_permissions',
        'can_view_timeclock',
        'locked',
        'timeclock_requirements'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    const PERMISSIONS = [
        'can_warn',
        'can_kick',
        'can_ban',
        'can_note',
        'can_commend',
        'can_view_staff',
        'can_manage_record',
        'can_manage_permissions',
        'can_view_timeclock'
    ];

    public static function usersWithRole($server_id, $id): Collection|array
    {
        // Get the role
        $role = self::where('server_id', $server_id)->find($id);

        $users = [];

        User::all()->each(function($user) use ($role, &$users) {
            if ($user->hasDiscordRole($role->guild, $role->id)) {
                $users[] = $user;
            }
        });

        return $users;
    }

    public function can($type) {
        $type = array_search($type, PlayerRecord::TYPES);
        $type = \Str::lower($type);
        $type = 'can_' . $type;

        return $this->$type;
    }
}

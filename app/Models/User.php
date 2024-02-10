<?php

namespace App\Models;

use App\Models\Hub\Application;
use App\Models\Hub\Ticket;
use App\Models\Staff\PlayerRecord;
use App\Models\Staff\ServerRole;
use App\Models\Staff\ServerTimeclock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jakyeru\Larascord\Traits\InteractsWithDiscord;
use Laravel\Sanctum\HasApiTokens;


/**
 * App\Models\User
 *
 * @property int $id
 * @property string $username
 * @property string|null $global_name
 * @property string|null $discriminator
 * @property string|null $email
 * @property string|null $avatar
 * @property bool|null $verified
 * @property string|null $banner
 * @property string|null $banner_color
 * @property string|null $accent_color
 * @property string $locale
 * @property string|null $signature
 * @property bool $mfa_enabled
 * @property int|null $premium_type
 * @property int|null $public_flags
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array|null $roles
 * @property-read \Jakyeru\Larascord\Models\DiscordAccessToken|null $accessToken
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Application> $applications
 * @property-read int|null $applications_count
 * @property-read string $tag
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Ticket> $tickets
 * @property-read int|null $tickets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccentColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBannerColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDiscriminator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGlobalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMfaEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePremiumType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePublicFlags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerified($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithDiscord;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'username',
        'global_name',
        'discriminator',
        'email',
        'avatar',
        'verified',
        'banner',
        'banner_color',
        'accent_color',
        'locale',
        'signature',
        'mfa_enabled',
        'premium_type',
        'public_flags',
        'roles',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'username' => 'string',
        'global_name' => 'string',
        'discriminator' => 'string',
        'email' => 'string',
        'avatar' => 'string',
        'verified' => 'boolean',
        'banner' => 'string',
        'banner_color' => 'string',
        'accent_color' => 'string',
        'locale' => 'string',
        'mfa_enabled' => 'boolean',
        'premium_type' => 'integer',
        'public_flags' => 'integer',
        'roles' => 'json',
    ];

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function applications() {
        return $this->hasMany(Application::class);
    }

    public function displayName() {
        return $this->global_name?? $this->username;
    }

    public static function info($id) {
        return self::findOrFail($id);
    }

    public function hasDiscordRole($guild, $roleid): bool
    {
        if( isset($this->roles[$guild]) ) {
            if(in_array($roleid, $this->roles[$guild])) {
                return true;
            }
        }
        return false;
    }

    public function getDiscordRoles() {
        return $this->roles;
    }

    public function isStaff(): bool
    {
        $roles = ServerRole::all();

        foreach($roles as $role) {
            if($this->hasDiscordRole($role->guild, $role->id)) {
                return true;
            }
        }

        return false;
    }

    public function isManagement(): bool
    {
        return $this->hasDiscordRole(env('DISCORD_GUILD_ID'), env('DISCORD_ROLE_MGMT'));
    }

    public function isOwner(): bool
    {
        return $this->hasDiscordRole(env('DISCORD_GUILD_ID'), env('DISCORD_ROLE_OWNER'));
    }

    /**
     * Staff Panel Functions
     */

    public function getHighestRole($server) {
        //get all the roles in ServerRole
        $roles = ServerRole::where('server_id', $server->id)->get();

        //if the user is owner, return the owner role
        if($this->isOwner()) {
            return ServerRole::where('id', env('DISCORD_ROLE_OWNER'))->first();
        }

        foreach($roles as $role) {
            if($this->hasDiscordRole($role->guild, $role->id)) {
                return $role;
            }
        }

        return 'No Role';
    }

    public function records() {
        return $this->hasMany(PlayerRecord::class, 'staff_id');
    }

    public function timeclocks() {
        return $this->hasMany(ServerTimeclock::class);
    }
}

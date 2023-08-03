<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jakyeru\Larascord\Traits\InteractsWithDiscord;
use Laravel\Sanctum\HasApiTokens;


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

    public function hasDiscordRole($roleid) {
        $discord_guild_id = env("DISCORD_GUILD_ID");

        if( isset($this->roles[$discord_guild_id]) ) {
            if(in_array($roleid, $this->roles[$discord_guild_id])) {
                return true;
            }
        }
        return false;
    }

    public function getDiscordRoles() {
        $discord_guild_id = env("DISCORD_GUILD_ID");

        return $this->roles[$discord_guild_id]?? null;
    }

    public function isManagement() {
        return $this->hasDiscordRole(env('DISCORD_ROLE_MGMT'));
    }

    public function isOwner() {
        return $this->hasDiscordRole(env('DISCORD_ROLE_OWNER'));
    }
}

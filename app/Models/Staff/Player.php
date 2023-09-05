<?php

namespace App\Models\Staff;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Staff\Player
 *
 * @property int $id
 * @property string $server_id
 * @property string $name
 * @property string $license
 * @property string|null $steam
 * @property string|null $discord
 * @property string $ip
 * @property array $old_licenses
 * @property int $trust_score
 * @property int $playtime
 * @property int $online
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Staff\PlayerBan|null $ban
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Staff\ServerDeath> $deaths
 * @property-read int|null $deaths_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Staff\PlayerRecord> $record
 * @property-read int|null $record_count
 * @property-read \App\Models\Staff\Server $server
 * @method static \Illuminate\Database\Eloquent\Builder|Player newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player query()
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereDiscord($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereOldLicenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player wherePlaytime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereSteam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereTrustScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'name',
        'license',
        'steam',
        'discord',
        'ip',
        'old_licenses',
        'trust_score',
        'playtime',
        'online',
    ];

    protected $casts = [
        'old_licenses' => 'json',
        'online' => 'boolean',
    ];

    const BASE_TRUSTSCORE = 50;
    const TRUSTSCORE_PER_HOUR = 1;

    public function server() {
        return $this->belongsTo(Server::class);
    }

    public function record() {
        return $this->hasMany(PlayerRecord::class);
    }

    public function activity() {
        return $this->hasMany(PlayerActivity::class);
    }

    public static function byLicense($license) {
        return Player::where('license', $license)->first();
    }

    public function isBanned()
    {
        // Get all records that have the type of Ban
        $banRecords = $this->record()
            ->where('type', PlayerRecord::TYPES['Ban'])
            ->orderByDesc('expiration_date')
            ->get();

        $latestBan = $banRecords->first();

        if ($banRecords->isEmpty()) return false;
        if ($latestBan->expiration_date === null) return $latestBan;

        // if it is in the future, return the ban, otherwise return false
        return Carbon::parse($latestBan->expiration_date)->isFuture() ? $latestBan : false;
    }

    public function deaths()
    {
        return $this->hasMany(ServerDeath::class);
    }

}

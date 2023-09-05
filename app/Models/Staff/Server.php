<?php

namespace App\Models\Staff;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * App\Models\Staff\Server
 *
 * @property string $id
 * @property string $name
 * @property string $ip
 * @property string $port
 * @property string $secret
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Staff\ServerChat> $chats
 * @property-read int|null $chats_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Staff\Player> $players
 * @property-read int|null $players_count
 * @method static \Illuminate\Database\Eloquent\Builder|Server newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server query()
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Server extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'ip',
        'port',
        'fetching_rate',
        'last_fetched'
    ];

    protected $casts = [
        'player_count' => 'json',
    ];

    public function players() {
        return $this->hasMany(Player::class);
    }

    public function records() {
        return $this->hasMany(PlayerRecord::class);
    }

    public function chats() {
        return $this->hasMany(ServerChat::class);
    }

    public function deaths() {
        return $this->hasMany(ServerDeath::class);
    }

    public function reports() {
        return $this->hasMany(ServerReport::class);
    }

    public function roles() {
        return $this->hasMany(ServerRole::class);
    }

    public function timeclocks() {
        return $this->hasMany(ServerTimeclock::class);
    }

    function getTopTimeclock()
    {
        $cacheKey = 'server_timeclock' . $this->id;

        if (Cache::has($cacheKey)) return Cache::get($cacheKey);

        // Now, you can use Eloquent to retrieve the top 5 players with the highest time
        $top5Players = ServerTimeclock::whereIn('type', [ServerTimeclock::TYPES['clock_out'], ServerTimeclock::TYPES['auto_clock_out']])
            ->selectRaw('user_id, SUM(time) as total_time')
            ->groupBy('user_id')
            ->orderByDesc('time')
            ->take(5)
            ->get();

        // Initialize an empty array to store the results
        $topPlayersWithInfo = [];

        foreach ($top5Players as $result) {
            $userId = $result->user_id;
            $playerInfo = [
                'user' => User::find($userId),
                'time' => $result->total_time,
            ];

            if ($playerInfo) {
                $topPlayersWithInfo[] = $playerInfo;
            }
        }

        Cache::put($cacheKey, $topPlayersWithInfo, now()->addMinutes(15));

        return $topPlayersWithInfo;
    }

    public function isValidKey() {
        $key = request()->headers->get('Secret');
        $decrypted = Crypt::decryptString($this->secret);

        if($decrypted != $key) abort(403);
    }

    public function isOnline() {
        $cacheKey = 'server_status_' . $this->id;

        if (Cache::has($cacheKey)) return Cache::get($cacheKey);

        try {
            $serverStatus = Http::timeout(2)->get('http://' . $this->ip . ':' . $this->port . '/staffpanel/online')->successful();
        } catch (Throwable) {
            $serverStatus = false;
        }

        Cache::put($cacheKey, $serverStatus, now()->addMinutes());
        return $serverStatus;
    }

    public function playerCount() {
        $cacheKey = 'server_player_count_' . $this->id;

        if (Cache::has($cacheKey)) return Cache::get($cacheKey);

        $count = Player::where('server_id', $this->id)->where('online', 1)->count();

        Cache::put($cacheKey, $count, now()->addMinutes());
        return $count;
    }
}

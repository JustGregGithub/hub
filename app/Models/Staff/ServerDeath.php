<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Staff\ServerDeath
 *
 * @property int $id
 * @property string $server_id
 * @property int|null $player_id
 * @property int $killer_id
 * @property string|null $cause
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Staff\Player|null $player
 * @property-read \App\Models\Staff\Server $server
 * @method static \Illuminate\Database\Eloquent\Builder|ServerDeath newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServerDeath newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServerDeath query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServerDeath whereCause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerDeath whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerDeath whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerDeath whereKillerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerDeath wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerDeath whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerDeath whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ServerDeath extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'player_id',
        'killer_id',
        'cause'
    ];

    public function server() {
        return $this->belongsTo(Server::class);
    }

    public function player() {
        return $this->belongsTo(Player::class);
    }
}

<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Staff\PlayerRecord
 *
 * @property int $id
 * @property string $server_id
 * @property int $player_id
 * @property int $type
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Staff\Player $player
 * @property-read \App\Models\Staff\Server $server
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRecord whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRecord wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRecord whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRecord whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PlayerRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'player_id',
        'staff_id',
        'type',
        'message',
        'expiration_date'
    ];

    const TYPES = [
        'Warn' => 10,
        'Kick' => 20,
        'Ban' => 30,
        'Note' => 40,
        'Commend' => 50,
    ];

    public function server() {
        return $this->belongsTo(Server::class);
    }

    public function player() {
        return $this->belongsTo(Player::class);
    }
}

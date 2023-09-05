<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Staff\ServerChat
 *
 * @property string $server_id
 * @property int $player_id
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Staff\Server $server
 * @method static \Illuminate\Database\Eloquent\Builder|ServerChat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServerChat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServerChat query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServerChat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerChat whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerChat wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerChat whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerChat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ServerChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'player_id',
        'message',
    ];

    public function server() {
        return $this->belongsTo(Server::class);
    }
}

<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'player_id',
        'message',
        'type',
    ];

    CONST TYPES = [
        'Join' => 10,
        'Leave' => 20,
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}

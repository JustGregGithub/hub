<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'player_id',
        'reported_id',
        'reason'
    ];

    public function server() {
        return $this->belongsTo(Server::class);
    }

    public function player() {
        return $this->belongsTo(Player::class);
    }

    public function reported() {
        return $this->belongsTo(Player::class, 'reported_id');
    }
}

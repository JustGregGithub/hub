<?php

namespace App\Models\Staff;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerTimeclock extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'user_id',
        'type',
        'time'
    ];

    const TYPES = [
        'clock_in' => 10,
        'clock_out' => 20,
        'auto_clock_out' => 30,
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

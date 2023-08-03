<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'type',
        'user_id',
        'content',
    ];

    const TYPE_REPLY = 0;
    const TYPE_NOTE = 1;

    protected $touches = ['ticket'];

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }

    function user() {
        return $this->belongsTo(User::class);
    }
}

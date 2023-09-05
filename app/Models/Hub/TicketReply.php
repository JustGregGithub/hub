<?php

namespace App\Models\Hub;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Hub\TicketReply
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $type
 * @property int $user_id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Hub\Ticket|null $ticket
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketReply whereUserId($value)
 * @mixin \Eloquent
 */
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

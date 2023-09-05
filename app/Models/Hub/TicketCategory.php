<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Hub\TicketCategory
 *
 * @property int $id
 * @property string $name
 * @property string $guild
 * @property string $role
 * @property int $deletable
 * @property int $is_hidden
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hub\Ticket> $tickets
 * @property-read int|null $tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereDeletable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereGuild($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereIsHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TicketCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'guild',
        'role',
        'is_hidden'
    ];

    public function tickets() {
        return $this->hasMany(Ticket::class, 'category_id', 'id');
    }

    public function statusCount($status = null) {
        return $this->tickets()->where('status', $status)->count();
    }

    public static function getRole($category_id) {
        return self::where('id', $category_id)->first()->role;
    }

    public static function getDefault() {
        return self::where('deletable', false)->first();
    }
}

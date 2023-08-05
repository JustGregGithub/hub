<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'assigned_person',
        'status',
        'title',
        'slug',
        'content',
        'pinned',
        'allowed_users',
        'priority'
    ];

    protected $casts = [
        'allowed_users' => 'array'
    ];

    CONST DEFAULT_STATUS = 'Open';
    const STATUSES = [
        'Open' => 10,
        'In-Progress' => 20,
        'On Hold' => 30,
        'Answered' => 31,
        'Awaiting Reply' => 40,
        'Transferred' => 50,
        'Escalated' => 60,
        'Closed' => 70
    ];

    const PRIORITIES = [
        'None' => 0,
        'Low' => 10,
        'Medium' => 20,
        'High' => 30
    ];

    public const ALLOWED_TAGS = "<p><br><b><strong><i><em><u><ul><ol><li><h1><h2><h3><h4><h5><h6><img><a><input>";

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function category() {
        return $this->belongsTo(TicketCategory::class);
    }

    // returns a list of all categories
    public function categories() {
        return TicketCategory::all();
    }

    // Supports optional variable to lookup a status name by number.
    public static function status($status_id) {
        return array_search($status_id, self::STATUSES);
    }

    public function priority($priority = null) {
        return array_search($priority?? $this->priority, self::PRIORITIES);
    }

    // Generates a save slug.
    public function makeSlug(): string {
        return Str::substr($this->id . '-' . Str::slug($this->title), 0, 25);
    }

    public static function statusColor($status_id) {
        switch($status_id) {
            case self::STATUSES['Open']:
                return 'green-500';
            case self::STATUSES['Awaiting Reply']:
            case self::STATUSES['In-Progress']:
                return 'orange-500';
            case self::STATUSES['On Hold']:
                return 'blue-500';
            case self::STATUSES['Closed']:
            case self::STATUSES['Answered']:
            case self::STATUSES['Escalated']:
            case self::STATUSES['Transferred']:
                return 'red-500';
        }

        return 'gray-500';
    }

    public function priorityColor() {
        switch($this->priority) {
            case self::PRIORITIES['None']:
                return 'gray-500';
            case self::PRIORITIES['Low']:
                return 'green-500';
            case self::PRIORITIES['Medium']:
                return 'orange-500';
            case self::PRIORITIES['High']:
                return 'red-500';
        }
    }

    public function replies() {
        return $this->hasMany(TicketReply::class)->with(['user']);
    }

    public function getAssigneeName() {
        if($this->assigned_person == null) return 'Unassigned';

        return User::find($this->assigned_person)->displayName();
    }
}

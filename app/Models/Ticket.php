<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'description',
        'attributed_user',
        'attributed_user_id',
        'priority',
        'status',
        'attributed_role_id',
        'solution'
    ];

    public function attributedUser(): BelongsTo {
        return $this->belongsTo(User::class, 'attributed_user_id');
    }

    public function askerUser(): BelongsTo {
        return $this->belongsTo(User::class, 'asker_user_id');
    }

    public function attributedRole(): BelongsTo {
        return $this->belongsTo(Role::class, 'attributed_role_id');
    }

    public function ticketMessages(): HasMany {
        return $this->hasMany(TicketMessage::class, 'ticket_id');
    }
}

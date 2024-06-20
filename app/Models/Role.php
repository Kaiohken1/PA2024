<?php

namespace App\Models;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    public function ticket(): HasMany {
        return $this->hasMany(Ticket::class, 'attributed_role_id');
    }
}
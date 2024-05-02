<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifcations extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'content',
        'read_at',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

}

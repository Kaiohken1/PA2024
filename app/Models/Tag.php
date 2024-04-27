<?php

namespace App\Models;

use App\Models\User;
use App\Models\Appartement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function tag(): BelongsTo {
        return $this->belongsTo(Appartement::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}

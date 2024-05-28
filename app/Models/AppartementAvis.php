<?php

namespace App\Models;

use App\Models\User;
use App\Models\Appartement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppartementAvis extends Model
{
    protected $table = 'appartement_avis';

    protected $fillable = [
        'rating_cleanness',
        'rating_price_quality',
        'rating_location',
        'rating_communication',
        'comment'
    ];

    public function appartement(): BelongsTo{
        return $this->belongsTo(Appartement::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fermeture extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'end',
        'title',
        'comment',
        'appartement_id',
        'daysWeek'
    ];

    protected $casts = [
        'daysWeek' => 'array',
    ];

    public function appartement(): BelongsTo {
        return $this->belongsTo(Appartement::class);
    }
}

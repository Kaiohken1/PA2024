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

    public function scopeSearch($query, $value)
    {
        $date = \DateTime::createFromFormat('d/m/Y', $value);
        if ($date) {
            $formattedDate = $date->format('Y-m-d');
            return $query->where('start', 'like', "%{$formattedDate}%")
                        ->orWhere('end', 'like', "%{$formattedDate}%")
                        ->orWhere('appartement_id', 'like', "%{$value}%")
                        ->orWhere('comment', 'like', "%{$value}%");
        }

        return $query->where('start', 'like', "%{$value}%")
                    ->orWhere('end', 'like', "%{$value}%")
                    ->orWhere('appartement_id', 'like', "%{$value}%")
                    ->orWhere('comment', 'like', "%{$value}%");

    }
}

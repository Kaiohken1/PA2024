<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use App\Models\Reservation;
use App\Models\AppartementAvis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Appartement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'surface',
        'guestCount',
        'roomCount',
        'description',
        'price',
        'image',
        'property_type',
        'city',
        'location_type',
        'recurringClosures',
    ];

    protected $casts = [
        'recurringClosures' => 'array', 
    ];

    protected $hidden = [
        'availabillity'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany {
        return $this->hasMany(AppartementImage::class);
    }

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }

    public function tags(): BelongsToMany {
        return $this->belongsToMany(Tag::class);
    }

    public function fermetures(): HasMany {
        return $this->hasMany(Fermeture::class);
    }

    public function avis(): HasMany {
        return $this->hasMany(AppartementAvis::class);
    }

    public function getRecurringClosures()
    {
        return $this->recurringClosures ?? [];
    }
}

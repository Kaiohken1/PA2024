<?php

namespace App\Models;

use App\Models\User;
use App\Models\Reservation;
use App\Models\Tag;
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
        'image'
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
}

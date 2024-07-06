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
use Illuminate\Database\Eloquent\SoftDeletes;

class Appartement extends Model
{
    use HasFactory, SoftDeletes;

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
        'active_flag',
        'postal_code'
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

    public function statut()
    {
        return $this->belongsTo(Statut::class, 'statut_id');
    }

    public function scopeSearch($query, $value)
    {
        return $query->where('id', 'like', "%{$value}%")
                ->orWhere('name', 'like', "%{$value}%")
                ->orWhere('city', 'like', "%{$value}%")
                ->orWhere('address', 'like', "%{$value}%")
                ->orWhere('location_type', 'like', "%{$value}%")
                ->orWhereHas('user', function ($query) use ($value) {
                    $query->where('name', 'like', "%{$value}%");
                });
    }
}

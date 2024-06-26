<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserAvis;
use App\Models\Appartement;
use App\Models\AppartementAvis;
use Illuminate\Database\Eloquent\Model;
use App\Events\Reservation as EventsReservation;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    protected $fillable = [
        'start_time',
        'end_time',
        'nombre_de_personne',
        'prix',
        'status',
        'commentaire',
        'content',
        'commission',
    ];

    protected $dispatchesEvents = [
        'created' => EventsReservation::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appartement()
    {
        return $this->belongsTo(Appartement::class, 'appartement_id')
                    ->withTrashed();

    }

    public function avis(): HasOne
    {
        return $this->hasOne(AppartementAvis::class);
    }
    public function UserAvis()
    {
    return $this->hasMany(UserAvis::class);
    }

    public function scopeSearch($query, $value)
    {
        return $query->where('id', 'like', "%{$value}%")
            ->orWhereHas('appartement', function ($query) use ($value) {
                $query->where('name', 'like', "%{$value}%");
            })
            ->orWhereHas('user', function ($query) use ($value) {
                $query->where('name', 'like', "%{$value}%")
                    ->orWhere('first_name', 'like', "%{$value}%")
                    ->orWhereRaw("CONCAT(name, ' ', first_name) LIKE ?", ["%{$value}%"]);
            });
    }
}

<?php

namespace App\Models;

use App\Models\User;
use App\Models\Appartement;
use App\Models\AppartementAvis;
use Illuminate\Database\Eloquent\Model;
use App\Events\Reservation as EventsReservation;

class Reservation extends Model
{
    protected $fillable = [
        'start_time',
        'end_time',
        'nombre_de_personne',
        'prix',
        'status',
        'commentaire',
        'content'
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
        return $this->belongsTo(Appartement::class, 'appartement_id');
    }

    public function avis(): HasOne
    {
        return $this->hasOne(AppartementAvis::class);
    }
}

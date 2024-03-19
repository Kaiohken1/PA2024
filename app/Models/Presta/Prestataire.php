<?php

namespace App\Models\Presta;

use App\Models\User;
use App\Models\Presta\Habilitation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prestataire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'tarif',
        'user_id',
    ];

    public function habilitations(): BelongsToMany
    { 
        return $this->belongsToMany(Habilitation::class, 'habilitations_prestataires', 'prestataire_id', 'service_id');
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function isValidated(): bool {
        return $this->statut === 'validé';
    }
}

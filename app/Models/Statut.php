<?php

namespace App\Models;

use App\Models\Intervention;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Statut extends Model
{
    use HasFactory;

    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'statut_id');
    }
}

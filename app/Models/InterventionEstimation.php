<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterventionEstimation extends Model
{
    use HasFactory;

    protected $fillable = [
        'intervention_id',
        'provider_id',
        'statut_id',
        'estimate',
    ];

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

}

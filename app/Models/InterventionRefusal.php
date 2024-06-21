<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterventionRefusal extends Model
{
    protected $fillable = [
        'intervention_id', 
        'provider_id', 
        'statut_id', 
        'refusal_reason', 
        'price', 
        'planned_date', 
        'planned_end_date',
        'estimate',
    ];

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
<?php

namespace App\Models;

use App\Models\Statut;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InterventionEstimation extends Model
{
    use HasFactory;

    protected $fillable = [
        'intervention_id',
        'provider_id',
        'statut_id',
        'estimate',
        'end_time',
        'price',
    ];

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);    
    }

    public function statut()
    {
        return $this->belongsTo(Statut::class);
    }

}

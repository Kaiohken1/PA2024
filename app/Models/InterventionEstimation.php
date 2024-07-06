<?php

namespace App\Models;

use App\Events\EstimationCreated;
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
        'refusal_reason',
        'commission',
    ];

    protected $dispatchesEvents = [
        'created' => EstimationCreated::class,
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

    public function scopeSearch($query, $value)
    {
        return $query->where('id', 'like', "%{$value}%")
                ->orWhereHas('provider', function ($query) use ($value) {
                    $query->where('email', 'like', "%{$value}%")
                            ->orWhere('name', 'like', "%{$value}%");
                });
    }
}

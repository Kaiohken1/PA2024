<?php

namespace App\Models;

use App\Models\User;
use App\Models\Absence;
use App\Models\Service;
use App\Models\Intervention;
use App\Events\ProviderCreated;
use MBarlow\Megaphone\HasMegaphone;
use App\Models\InterventionEstimation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use HasFactory, Notifiable, HasMegaphone, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email', 
        'description',
        'avatar', 
        'availability'
    ];

    protected $hidden = [
        'status'
    ];

    protected $dispatchesEvents = [
        'created' => ProviderCreated::class,
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'provider_services')
                    ->withPivot(['description', 'service_id', 'price_scale'])
                    ->withTimestamps();
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function documents(): BelongsToMany {
        return $this->belongsToMany(Document::class, 'providers_documents')
                    ->withPivot(['document'])
                    ->withTimestamps();
    }

    public function interventions() : HasMany {
        return $this->hasMany(Intervention::class)
                    ->withTrashed();
    }

    public function intervention_events() : HasMany {
        return $this->hasMany(InterventionEvent::class);
    }

    public function estimations() {
        return $this->hasMany(InterventionEstimation::class);
    }

    public function absences() : HasMany {
        return $this->hasMany(Absence::class);
    }

    public function hidden() {
        return $this->belongsToMany(Intervention::class, 'hidden_interventions');
    }

    public function selectedCities()
    {
        return $this->hasMany(ProviderCitySelection::class);
    }

    public function scopeSearch($query, $value)
    {
        return $query->where('id', 'like', "%{$value}%")
                ->orWhere('name', 'like', "%{$value}%")
                ->orWhere('email', 'like', "%{$value}%")
                ->orWhereHas('services', function($query) use ($value) {
                    $query->where('services.name', 'like', "%{$value}%");
                });;
    }
}

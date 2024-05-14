<?php

namespace App\Models;

use App\Events\ProviderCreated;
use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email', 
        'description',
        'avatar', 
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
}

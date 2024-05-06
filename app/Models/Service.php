<?php

namespace App\Models;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'flexPrice'
    ];

    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(Provider::class, 'provider_services')
                    ->withPivot(['price', 'flexPrice', 'habilitationImg', 'provider_description'])
                    ->withTimestamps();
    }

    public function parameters(): HasMany {
        return $this->hasMany(ServiceParameter::class);
    }
}

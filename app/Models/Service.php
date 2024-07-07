<?php

namespace App\Models;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Versionable\VersionableTrait;

class Service extends Model
{
    use HasFactory, VersionableTrait, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'description',
        'flexPrice',
        'active_flag',
        'category_id',
        'role_id',
        'hasRange',
    ];

    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(Provider::class, 'provider_services')
                    ->withPivot(['price', 'flexPrice', 'habilitationImg', 'provider_description'])
                    ->withTimestamps();
    }

    public function provider(): BelongsToMany
    {
        return $this->belongsToMany(Provider::class, 'provider_services');
    }

    public function parameters(): HasMany {
        return $this->hasMany(ServiceParameter::class);
    }

    public function documents() : BelongsToMany {
        return $this->BelongsToMany(Document::class, 'services_documents');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function scopeSearch($query, $value)
    {
        return $query->where('id', 'like', "%{$value}%")
                ->orWhere('name', 'like', "%{$value}%")
                ->orWhereHas('category', function ($query) use ($value) {
                    $query->where('name', 'like', "%{$value}%");
                });
    }
}

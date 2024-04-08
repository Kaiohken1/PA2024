<?php

namespace App\Models;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'email', 
        'description',
        'avatar', 
    ];

    protected $hidden = [
        'status'
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'provider_services')
                    ->withPivot(['price', 'flexPrice', 'habilitationImg', 'description'])
                    ->withTimestamps();
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}

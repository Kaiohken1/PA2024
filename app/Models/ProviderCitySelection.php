<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderCitySelection extends Model
{
    use HasFactory;

    protected $fillable = ['provider_id', 'city'];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}

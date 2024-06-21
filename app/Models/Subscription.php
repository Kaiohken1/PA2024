<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'monthly_price',
        'annual_price',
        'permanent_discount',
        'renewal_bonus',
        'logo',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('free_service_count', 'last_free_service_date')
                    ->withTimestamps();
    }
    
}

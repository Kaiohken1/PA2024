<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'name', 
        'stripe_id', 
        'stripe_status', 
        'stripe_price', 
        'quantity', 
        'trial_ends_at', 
        'ends_at', 
        'free_service_count', 
        'last_free_service_date',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

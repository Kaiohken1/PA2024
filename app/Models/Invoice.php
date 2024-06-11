<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider_id',
        'intervention_id',
        'price',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function provider() {
        return $this->belongsTo(Provider::class);
    }

    public function intervention() {
        return $this->belongsTo(Intervention::class);
    }
}

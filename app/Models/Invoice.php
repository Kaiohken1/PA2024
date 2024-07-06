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
        'pdf',
        'role',
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

    public function scopeSearch($query, $value)
    {
        return $query->where('id', 'like', "%{$value}%")
            ->orWhereHas('provider', function ($query) use ($value) {
                $query->where('name', 'like', "%{$value}%");
            })
            ->orWhereHas('user', function ($query) use ($value) {
                $query->where('name', 'like', "%{$value}%")
                    ->orWhere('first_name', 'like', "%{$value}%")
                    ->orWhereRaw("CONCAT(name, ' ', first_name) LIKE ?", ["%{$value}%"]);
            });
    }
}

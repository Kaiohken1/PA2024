<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title', 'start', 'end'];

    public function provider() : BelongsTo {
        return $this->belongsTo(Provider::class);
    }
}

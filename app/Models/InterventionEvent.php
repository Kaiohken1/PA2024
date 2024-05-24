<?php

namespace App\Models;

use App\Models\Provider;
use App\Models\Intervention;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InterventionEvent extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title', 'start', 'end',];

    public function intervention() : BelongsTo {
        return $this->belongsTo(Intervention::class);
    }

    public function provider() : BelongsTo {
        return $this->belongsTo(Provider::class);
    }
}

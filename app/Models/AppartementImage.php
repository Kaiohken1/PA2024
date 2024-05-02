<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppartementImage extends Model
{
    protected $table = 'appartement_image';

    protected $fillable = [
        'image'
    ];

    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }
}

<?php

namespace App\Models\Presta;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habilitation extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description'];
}

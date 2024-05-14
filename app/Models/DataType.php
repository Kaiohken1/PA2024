<?php

namespace App\Models;

use App\Models\ServiceParameter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'validation_rule'];

    public function serviceParameters()
    {
        return $this->hasMany(ServiceParameter::class);
    }
}

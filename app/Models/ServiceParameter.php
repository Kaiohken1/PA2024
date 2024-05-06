<?php

namespace App\Models;

use App\Models\DataType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceParameter extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'data_type_id'];

    public function service() : BelongsTo {
        return $this->belongsTo(Service::class);
    }

    public function dataType()
    {
        return $this->belongsTo(DataType::class);
    }
}

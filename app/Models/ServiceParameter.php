<?php

namespace App\Models;

use App\Models\DataType;
use App\Models\Intervention;
use Mpociot\Versionable\Version;
use Illuminate\Database\Eloquent\Model;
use Mpociot\Versionable\VersionableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceParameter extends Model
{
    use HasFactory, VersionableTrait;

    protected $fillable = ['name', 'data_type_id'];

    public function service() : BelongsTo {
        return $this->belongsTo(Service::class);
    }

    public function dataType()
    {
        return $this->belongsTo(DataType::class);
    }

    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class, 'service_parameters_values');
    }

    public function version() {
        return $this->belongsTo(Version::class, 'service_parameters_values')
            ->withPivot(['parameter_value']);
    }
}

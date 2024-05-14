<?php

namespace App\Models;

use App\Models\User;
use App\Models\Statut;
use App\Models\Service;
use App\Models\Provider;
use App\Events\Reservation;
use App\Models\Appartement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mpociot\Versionable\Version;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'commentaire',
        'appartement_id',
        'service_id',
        'price',
    ];


    protected $hidden = [
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function service() {
        return $this->belongsTo(Service::class)
                ->withTrashed();

    }

    public function services() {
        return $this->belongsTo(Version::class, 'service_version');
    }

    public function service_parameters() {
        return $this->belongsToMany(ServiceParameter::class, 'service_parameters_values')
                    ->withPivot(['value', 'service_parameter_id', 'parameter_version'])
                    ->withTrashed();
    }

    public function statut() {
        return $this->belongsTo(Statut::class, 'statut_id');
    }
}

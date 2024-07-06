<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class CheckEventConflict implements ValidationRule
{
    protected $provider_id;
    protected $start_time;

    public function __construct($provider_id, $start_time)
    {
        $this->provider_id = $provider_id;
        $this->start_time = date("Y-m-d H:i:s", strtotime($start_time));
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $end_time = date("Y-m-d H:i:s", strtotime($value)); 

        $conflict = DB::table('intervention_events')
            ->where('provider_id', $this->provider_id)
            ->where(function ($query) use ($end_time) {
                $query->whereBetween('start', [$this->start_time, $end_time])
                      ->orWhereBetween('end', [$this->start_time, $end_time])
                      ->orWhere(function ($query) use ($end_time) {
                          $query->where('start', '<=', $this->start_time)
                                ->where('end', '>=', $end_time);
                      });
            })
            ->exists();

        if ($conflict) {
            $fail('Vous avez déjà une intervention à cette date-là, veuillez vous référer à votre calendrier.');
        }
    }
}

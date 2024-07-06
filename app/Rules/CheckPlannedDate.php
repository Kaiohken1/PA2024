<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Intervention;

class CheckPlannedDate implements ValidationRule
{
    protected $intervention;

    /**
     * Create a new rule instance.
     *
     * @param  \App\Models\Intervention  $intervention
     * @return void
     */
    public function __construct($id)
    {
        $this->intervention = Intervention::findOrFail($id);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strtotime($value) <= strtotime($this->intervention->planned_date)) {
            $fail('La date de fin prévue doit être donnée après celle de la date souhaitée');
        }
    }
}

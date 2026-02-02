<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RoleHierarchy implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $ranks = [
            'admin' => 3,
            'endurego_internal' => 2,
            'client' => 1,
        ];

        $userRank = auth()->user()->getRoleRank();
        $targetRank = $ranks[$value] ?? 0;

        if ($targetRank > $userRank) {
            $fail('You cannot assign a role higher than your own.');
        }
    }
}

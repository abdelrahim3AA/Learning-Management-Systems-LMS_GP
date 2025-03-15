<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;

class UserRoleValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    private $userRole;
    public function __construct(string $userRole)
    {
        $this->userRole = $userRole;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::findOrFail($value);

        if (!$user || $user->role !== $this->userRole)
        {
            $fail("The selected $attribute must have the role of {$this->userRole}.");
        }
    }
}

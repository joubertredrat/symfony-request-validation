<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class CreditCard extends Constraint
{
    public string $message = 'Expected valid credit card number.';

    public string $mode = 'strict';
}
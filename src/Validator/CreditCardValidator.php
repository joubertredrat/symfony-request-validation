<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CreditCardValidator extends ConstraintValidator
{
    private const REGEX = '/^(4|5){1}[0-9]{15}$/';

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CreditCard) {
            throw new UnexpectedTypeException($constraint, CreditCard::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!preg_match(self::REGEX, $value)) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation()
            ;
        }
    }
}
<?php

declare(strict_types=1);

namespace App\Request;

use App\Validator\CreditCard;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class CreateTransactionRequest extends AbstractJsonRequest
{
    const CURRENCY_BRL = 'R$';

    #[NotBlank(message: 'I dont like this field empty')]
    #[Type('string')]
    public readonly string $firstName;

    #[NotBlank(message: 'I dont like this field empty')]
    #[Type('string')]
    public readonly string $lastName;

    #[NotBlank()]
    #[Type('string')]
    #[CreditCard()]
    public readonly string $cardNumber;

    #[NotBlank()]
    #[Positive()]
    public readonly int $amount;

    #[NotBlank()]
    #[Type('int')]
    #[Range(
        min: 1,
        max: 12,
        notInRangeMessage: 'Expected to be between {{ min }} and {{ max }}, got {{ value }}',
    )]
    public readonly int $installments;

    #[Type('string')]
    public ?string $description = null;

    public function getFullName(): string
    {
        return \sprintf('%s %s', $this->firstName, $this->lastName);
    }

    public function getAmountBRL(): string
    {
        if ($this->amount < 100) {
            return \sprintf('%s 0,%d', self::CURRENCY_BRL, $this->amount);
        }

        return \sprintf(
            '%s %s', 
            self::CURRENCY_BRL, 
            \number_format($this->amount / 100, 2, ',', '.'),
        );
    }
}
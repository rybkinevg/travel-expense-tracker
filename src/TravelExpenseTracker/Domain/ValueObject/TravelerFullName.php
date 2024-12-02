<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\ValueObject;

final readonly class TravelerFullName
{
    public function __construct(
        protected string $firstName,
        protected ?string $lastName = null,
    ) {}

    final public function value(): string
    {
        if (null === $this->lastName) {
            return $this->firstName;
        }

        return $this->firstName . ' ' . $this->lastName;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): ?string
    {
        return $this->lastName;
    }
}

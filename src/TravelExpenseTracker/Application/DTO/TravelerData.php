<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Application\DTO;

use App\TravelExpenseTracker\Domain\Entity\Traveler;

final readonly class TravelerData
{
    public function __construct(
        public int $id,
        public string $username,
        public string $firstName,
        public ?string $lastName,
        public string $fullName,
    ) {}

    public static function fromEntity(Traveler $traveler): self
    {
        return new self(
            id: $traveler->getId()->value(),
            username: $traveler->getChatMemberUsername()->value(),
            firstName: $traveler->getFullName()->firstName(),
            lastName: $traveler->getFullName()->lastName(),
            fullName: $traveler->getFullName()->value(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'fullName' => $this->fullName,
        ];
    }
}

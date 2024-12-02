<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Application\DTO;

use App\TravelExpenseTracker\Domain\Entity\Traveler;
use App\TravelExpenseTracker\Domain\Entity\Trip;

final readonly class TripData
{
    public function __construct(
        public int $id,
        public string $chatId,
        public string $title,
        public bool $isActive,
        public ?int $durationInDays,
        public ?int $durationInHours,
        public string $startedAt,
        public ?string $completedAt,
        public array $travelers = [],
    ) {}

    public static function fromEntity(Trip $trip): self
    {
        $travelers = array_map(
            static fn (Traveler $traveler) => TravelerData::fromEntity($traveler),
            $trip->getTravelers()->toArray()
        );

        return new self(
            id: $trip->getId()->value(),
            chatId: $trip->getChatId()->value(),
            title: $trip->getTitle()->value(),
            isActive: $trip->isActive(),
            durationInDays: $trip->getDurationInDays(),
            durationInHours: $trip->getDurationInHours(),
            startedAt: $trip->getStartedAt()?->format('d.m.Y'),
            completedAt: $trip->getCompletedAt()?->format('d.m.Y'),
            travelers: $travelers,
        );
    }

    public function toArray(): array
    {
        $travelers = array_map(
            static fn (TravelerData $data) => $data->toArray(),
            $this->travelers
        );

        return [
            'id' => $this->id,
            'chatId' => $this->chatId,
            'title' => $this->title,
            'isActive' => $this->isActive,
            'durationInDays' => $this->durationInDays,
            'durationInHours' => $this->durationInHours,
            'startedAt' => $this->startedAt,
            'completedAt' => $this->completedAt,
            'travelers' => $travelers,
        ];
    }
}

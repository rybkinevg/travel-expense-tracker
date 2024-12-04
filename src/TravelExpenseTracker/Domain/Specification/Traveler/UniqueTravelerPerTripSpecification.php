<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Specification\Traveler;

use App\Shared\Domain\Specification\SpecificationInterface;
use App\TravelExpenseTracker\Domain\Entity\Traveler;
use App\TravelExpenseTracker\Domain\Repository\TravelerRepositoryInterface;

/**
 * @template-implements SpecificationInterface<Traveler>
 */
final readonly class UniqueTravelerPerTripSpecification implements SpecificationInterface
{
    public function __construct(
        private TravelerRepositoryInterface $travelerRepository,
    ) {}

    public function isSatisfiedBy(mixed $item): bool
    {
        $tripId = $item->getTrip()?->getId();

        if (null === $tripId) {
            return true;
        }

        $traveler = $this->travelerRepository->findByTripIdAndChatMemberUsername(
            $tripId,
            $item->getChatMemberUsername()
        );

        return null === $traveler;
    }
}

<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Specification\Trip;

use App\Shared\Domain\Specification\SpecificationInterface;
use App\TravelExpenseTracker\Domain\Entity\Trip;
use App\TravelExpenseTracker\Domain\Repository\TripRepositoryInterface;

/**
 * @template-implements SpecificationInterface<Trip>
 */
final readonly class UniqueTripPerChatSpecification implements SpecificationInterface
{
    public function __construct(
        private TripRepositoryInterface $tripRepository,
    ) {}

    public function isSatisfiedBy(mixed $item): bool
    {
        return null === $this->tripRepository->findActiveByChatId($item->getChatId());
    }
}

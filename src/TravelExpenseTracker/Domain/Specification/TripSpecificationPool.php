<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Specification;

use App\Shared\Domain\Specification\SpecificationPoolInterface;
use App\TravelExpenseTracker\Domain\Specification\Traveler\UniqueTravelerPerTripSpecification;
use App\TravelExpenseTracker\Domain\Specification\Trip\UniqueTripPerChatSpecification;

final readonly class TripSpecificationPool implements SpecificationPoolInterface
{
    public function __construct(
        public UniqueTripPerChatSpecification $uniqueTripPerChatSpecification,
        public UniqueTravelerPerTripSpecification $uniqueTravelerPerTripSpecification,
    ) {}
}

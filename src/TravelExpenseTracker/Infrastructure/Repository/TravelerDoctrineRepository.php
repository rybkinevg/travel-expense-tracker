<?php

/**
 * @noinspection PhpMultipleClassDeclarationsInspection
 */

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Repository;

use App\TravelExpenseTracker\Domain\Entity\Traveler;
use App\TravelExpenseTracker\Domain\Repository\TravelerRepositoryInterface;
use App\TravelExpenseTracker\Domain\ValueObject\ChatMemberUsername;
use App\TravelExpenseTracker\Domain\ValueObject\TripId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<Traveler>
 */
final class TravelerDoctrineRepository extends ServiceEntityRepository implements TravelerRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Traveler::class);
    }

    public function findByTripIdAndChatMemberUsername(
        TripId $tripId,
        ChatMemberUsername $chatMemberUsername,
    ): ?Traveler {
        $collection = $this->findByTripIdAndChatMemberUsernames($tripId, $chatMemberUsername);

        return $collection->first() ?: null;
    }

    public function findByTripIdAndChatMemberUsernames(
        TripId $tripId,
        ChatMemberUsername ...$chatMemberUsernames,
    ): Collection {
        $chatMemberUsernameValues = array_map(
            static fn (ChatMemberUsername $chatMemberUsername): string => $chatMemberUsername->value(),
            $chatMemberUsernames
        );

        $items = $this->findBy([
            'trip' => $tripId,
            'chatMemberUsername.value' => $chatMemberUsernameValues,
        ]);

        return new ArrayCollection($items);
    }
}

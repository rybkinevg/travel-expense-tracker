<?php

/**
 * @noinspection PhpMultipleClassDeclarationsInspection
 */

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Repository;

use App\TravelExpenseTracker\Domain\Entity\Trip;
use App\TravelExpenseTracker\Domain\Repository\TripRepositoryInterface;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;
use App\TravelExpenseTracker\Domain\ValueObject\TripId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<Trip>
 */
final class TripDoctrineRepository extends ServiceEntityRepository implements TripRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function findOneById(TripId $id): ?Trip
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function save(Trip $trip): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($trip);
        $entityManager->flush();
    }

    public function findActiveByChatId(ChatId $chatId): ?Trip
    {
        return $this->findOneBy([
            'chatId.value' => $chatId->value(),
            'isActive' => true,
        ]);
    }

    public function findLastCompletedByChatId(ChatId $chatId): ?Trip
    {
        $criteria = [
            'chatId.value' => $chatId->value(),
            'isActive' => false,
        ];

        $orderBy = [
            'completedAt' => 'DESC',
        ];

        return $this->findOneBy($criteria, $orderBy);
    }
}

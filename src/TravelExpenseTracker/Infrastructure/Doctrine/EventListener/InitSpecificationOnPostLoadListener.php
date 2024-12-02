<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Doctrine\EventListener;

use App\Shared\Domain\Specification\SpecificationPoolInterface;
use App\TravelExpenseTracker\Domain\Specification\TripSpecificationPool;
use Doctrine\ORM\Event\PostLoadEventArgs;

final readonly class InitSpecificationOnPostLoadListener
{
    public function __construct(
        private TripSpecificationPool $tripSpecificationPool,
    ) {}

    public function postLoad(PostLoadEventArgs $args): void
    {
        $entity = $args->getObject();

        $reflect = new \ReflectionClass($entity);

        foreach ($reflect->getProperties() as $property) {
            $type = $property->getType();

            if (is_null($type) || $property->isInitialized($entity)) {
                continue;
            }

            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                // initialize specifications
                $interfaces = class_implements($type->getName());

                if (isset($interfaces[SpecificationPoolInterface::class])) {
                    $property->setValue($entity, $this->tripSpecificationPool);

                    break;
                }
            }
        }
    }
}

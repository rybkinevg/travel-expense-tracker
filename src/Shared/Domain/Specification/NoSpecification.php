<?php

declare(strict_types=1);

namespace App\Shared\Domain\Specification;

final readonly class NoSpecification implements SpecificationInterface
{
    public function __construct(
        private SpecificationInterface $specification,
    ) {}

    public function isSatisfiedBy(mixed $item): bool
    {
        return !$this->specification->isSatisfiedBy($item);
    }
}

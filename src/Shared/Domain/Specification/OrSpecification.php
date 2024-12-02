<?php

declare(strict_types=1);

namespace App\Shared\Domain\Specification;

final readonly class OrSpecification implements SpecificationInterface
{
    private array $specifications;

    public function __construct(
        SpecificationInterface ...$specifications
    ) {
        $this->specifications = $specifications;
    }

    public function isSatisfiedBy(mixed $item): bool
    {
        foreach ($this->specifications as $specification) {
            if ($specification->isSatisfiedBy($item)) {
                return true;
            }
        }

        return false;
    }
}

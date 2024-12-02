<?php

declare(strict_types=1);

namespace App\Shared\Domain\Specification;

/**
 * @template T
 */
interface SpecificationInterface
{
    /**
     * @param T $item
     */
    public function isSatisfiedBy(mixed $item): bool;
}

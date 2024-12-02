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
     *
     * @return bool
     */
    public function isSatisfiedBy(mixed $item): bool;
}

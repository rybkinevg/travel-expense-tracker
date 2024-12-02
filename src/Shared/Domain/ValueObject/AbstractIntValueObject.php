<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class AbstractIntValueObject extends AbstractValueObject
{
    protected int $value;

    /**
     * @throws \UnexpectedValueException
     */
    public function __construct(
        int $value
    ) {
        $this->assertValueIsGreaterThan($value, 0);

        $this->value = $value;
    }

    final public function value(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value() === $other->value();
    }

    /**
     * @throws \UnexpectedValueException
     */
    protected function assertValueIsGreaterThan(int $value, int $min): void
    {
        if ($value > $min) {
            return;
        }

        $message = sprintf(
            'Число [%s] должно быть больше чем [%s]',
            $value,
            $min
        );

        throw new \UnexpectedValueException($message);
    }
}

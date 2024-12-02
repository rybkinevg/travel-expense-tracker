<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class AbstractStringValueObject extends AbstractValueObject
{
    protected string $value;

    /**
     * @throws \UnexpectedValueException
     */
    public function __construct(
        string $value
    ) {
        $this->value = $this->normalize($value);
    }

    final public function value(): string
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
    protected function normalize(string $value): string
    {
        $value = trim($value);

        $this->assertValueLengthIsGreaterThan($value, 0);
        $this->assertValueLengthIsLessThan($value, 255);

        return $value;
    }

    protected function assertValueLengthIsGreaterThan(string $value, int $min): void
    {
        $length = mb_strlen($value);

        if ($length > $min) {
            return;
        }

        $message = ($length === 0)
            ? 'Строка не должна быть пустой'
            : "Строка [$value] слишком короткая (минимальная длина: [$min])";

        throw new \UnexpectedValueException($message);
    }

    protected function assertValueLengthIsLessThan(string $value, int $max, int $maxDisplayLength = 20): void
    {
        $length = mb_strlen($value);

        if ($length < $max) {
            return;
        }

        $message = sprintf(
            'Строка [%s] слишком длинная (максимальная длина: [%s])',
            $length > $maxDisplayLength ? mb_substr($value, 0, $maxDisplayLength) . '...' : $value,
            $max
        );

        throw new \UnexpectedValueException($message);
    }
}

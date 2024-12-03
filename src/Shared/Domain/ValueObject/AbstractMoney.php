<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

/** @phpstan-consistent-constructor */
abstract readonly class AbstractMoney extends AbstractValueObject
{
    private const int DECIMAL_SCALE = 2;

    protected string $value;

    /**
     * @throws \UnexpectedValueException
     */
    public function __construct(string $value)
    {
        $this->value = $this->normalize($value);
    }

    final public function value(): string
    {
        return $this->value;
    }

    public function isZero(): bool
    {
        return $this->equals(new static('0'));
    }

    public function equals(AbstractMoney $money): bool
    {
        return 0 === bccomp($this->value, $money->value, self::DECIMAL_SCALE);
    }

    public function add(AbstractMoney $amount): static
    {
        $value = bcadd($this->value, $amount->value, self::DECIMAL_SCALE);

        return new static($value);
    }

    public function sub(AbstractMoney $amount): static
    {
        $value = bcsub($this->value, $amount->value, self::DECIMAL_SCALE);

        return new static($value);
    }

    public function div(AbstractMoney $amount): static
    {
        $value = bcdiv($this->value, $amount->value, self::DECIMAL_SCALE);

        return new static($value);
    }

    public function compare(AbstractMoney $amount): int
    {
        return bccomp($this->value, $amount->value, self::DECIMAL_SCALE);
    }

    public function isGreaterThan(AbstractMoney $amount): bool
    {
        return 1 === $this->compare($amount);
    }

    public function isLessThan(AbstractMoney $amount): bool
    {
        return -1 === $this->compare($amount);
    }

    /**
     * @throws \UnexpectedValueException
     */
    protected function normalize(string $value): string
    {
        $value = trim($value);

        $this->assertValueIsNumeric($value);

        return bcadd('0', $value, self::DECIMAL_SCALE);
    }

    /**
     * @throws \UnexpectedValueException
     */
    protected function assertValueIsNumeric(string $value): void
    {
        if ('' !== $value && preg_match('/^(-)?[0-9]+(\\.[0-9]+)?$/', $value)) {
            return;
        }

        $message = "Unsupported money format: [$value]";

        throw new \UnexpectedValueException($message);
    }
}

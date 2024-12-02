<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Entity;

use App\TravelExpenseTracker\Domain\ValueObject\ExpenseAmount;
use App\TravelExpenseTracker\Domain\ValueObject\ExpenseDescription;
use App\TravelExpenseTracker\Domain\ValueObject\ExpenseId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class Expense
{
    private readonly ?ExpenseId $id;
    private ?Trip $trip = null;
    private ?Traveler $payer = null;
    private readonly ExpenseDescription $description;
    private readonly ExpenseAmount $amount;
    private readonly \DateTimeInterface $createdAt;

    private Collection $debtors;

    public function __construct(
        ExpenseDescription $description,
        ExpenseAmount $amount,
    ) {
        $this->description = $description;
        $this->amount = $amount;
        $this->createdAt = new \DateTimeImmutable();

        $this->debtors = new ArrayCollection();
    }

    // Getters

    public function getId(): ?ExpenseId
    {
        return $this->id;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function getPayer(): ?Traveler
    {
        return $this->payer;
    }

    public function getDescription(): ExpenseDescription
    {
        return $this->description;
    }

    public function getAmount(): ExpenseAmount
    {
        return $this->amount;
    }

    public function getDebtors(): Collection
    {
        return $this->debtors;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    // Methods

    public function setTrip(Trip $trip): void
    {
        $this->trip = $trip;
    }

    public function setPayer(Traveler $traveler): void
    {
        $this->payer = $traveler;
    }

    public function addDebtor(Traveler $debtor): void
    {
        if ($this->debtors->contains($debtor)) {
            return;
        }

        $this->debtors->add($debtor);
    }
}

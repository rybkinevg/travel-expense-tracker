<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Entity;

use App\TravelExpenseTracker\Domain\Exception\TravelerAlreadyExistException;
use App\TravelExpenseTracker\Domain\Exception\TripAlreadyCompletedException;
use App\TravelExpenseTracker\Domain\Exception\TripAlreadyExistException;
use App\TravelExpenseTracker\Domain\Specification\TripSpecificationPool;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;
use App\TravelExpenseTracker\Domain\ValueObject\TripId;
use App\TravelExpenseTracker\Domain\ValueObject\TripTitle;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

// TODO: ChatId needs to be reworked if communication chanel will be changed from Telegram to another
final class Trip
{
    private ?TripId $id;
    private readonly ChatId $chatId;
    private readonly TripTitle $title;
    private bool $isActive = false;
    private ?\DateTimeInterface $startedAt = null;
    private ?\DateTimeInterface $completedAt = null;

    private Collection $travelers;
    private Collection $expenses;

    private readonly TripSpecificationPool $specificationPool;

    public function __construct(
        ChatId $chatId,
        TripTitle $title,
        TripSpecificationPool $specificationPool,
    ) {
        $this->chatId = $chatId;
        $this->title = $title;

        $this->travelers = new ArrayCollection();
        $this->expenses = new ArrayCollection();

        $this->specificationPool = $specificationPool;
    }

    // Getters

    public function getId(): ?TripId
    {
        return $this->id;
    }

    public function getChatId(): ChatId
    {
        return $this->chatId;
    }

    public function getTitle(): TripTitle
    {
        return $this->title;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getCompletedAt(): ?\DateTimeInterface
    {
        return $this->completedAt;
    }

    public function getTravelers(): Collection
    {
        return $this->travelers;
    }

    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    // Methods

    public function start(): void
    {
        if ($this->isCompleted()) {
            throw TripAlreadyCompletedException::alreadyCompleted();
        }

        $isUniqueTripInChat = $this
            ->specificationPool
            ->uniqueTripPerChatSpecification
            ->isSatisfiedBy($this)
        ;

        if (!$isUniqueTripInChat) {
            throw TripAlreadyExistException::uniquePerChat();
        }

        $this->isActive = true;
        $this->startedAt = new \DateTimeImmutable();
    }

    public function isCompleted(): bool
    {
        return false === $this->isActive && null !== $this->completedAt;
    }

    public function complete(): void
    {
        if ($this->isCompleted()) {
            return;
        }

        $this->isActive = false;
        $this->completedAt = new \DateTimeImmutable();
    }

    public function calcDuration(): ?\DateInterval
    {
        if (null === $this->startedAt) {
            return null;
        }

        $diffDate = $this->completedAt ?? new \DateTimeImmutable();

        return $this->startedAt->diff($diffDate);
    }

    public function getDurationInHours(): ?int
    {
        return $this->calcDuration()?->h;
    }

    public function getDurationInDays(): ?int
    {
        return $this->calcDuration()?->d;
    }

    public function addTraveler(Traveler $traveler): void
    {
        if ($this->travelers->contains($traveler)) {
            return;
        }

        $traveler->setTrip($this);

        $isUniqueTraveler = $this
            ->specificationPool
            ->uniqueTravelerPerTripSpecification
            ->isSatisfiedBy($traveler)
        ;

        if (!$isUniqueTraveler) {
            throw TravelerAlreadyExistException::uniquePerTrip($traveler->getChatMemberUsername());
        }

        $this->travelers->add($traveler);
    }

    public function addExpense(Expense $expense): void
    {
        if ($this->expenses->contains($expense)) {
            return;
        }

        $expense->setTrip($this);

        $this->expenses->add($expense);
    }
}

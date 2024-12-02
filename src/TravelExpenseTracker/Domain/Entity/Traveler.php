<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Entity;

use App\TravelExpenseTracker\Domain\ValueObject\ChatMemberUsername;
use App\TravelExpenseTracker\Domain\ValueObject\TravelerFullName;
use App\TravelExpenseTracker\Domain\ValueObject\TravelerId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

// TODO: ChatMemberId needs to be reworked if communication chanel will be changed from Telegram to another

/**
 * @final
 */
class Traveler
{
    private TravelerId $id;
    private ChatMemberUsername $chatMemberUsername;
    private ?Trip $trip = null;
    private TravelerFullName $fullName;

    private Collection $expenses;

    public function __construct(
        ChatMemberUsername $chatMemberUsername,
        TravelerFullName $fullName,
    ) {
        $this->chatMemberUsername = $chatMemberUsername;
        $this->fullName = $fullName;

        $this->expenses = new ArrayCollection();
    }

    # Getters

    public function getId(): TravelerId
    {
        return $this->id;
    }

    public function getChatMemberUsername(): ChatMemberUsername
    {
        return $this->chatMemberUsername;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function getFullName(): TravelerFullName
    {
        return $this->fullName;
    }

    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    # Methods

    public function setTrip(Trip $trip): void
    {
        $this->trip = $trip;
    }
}

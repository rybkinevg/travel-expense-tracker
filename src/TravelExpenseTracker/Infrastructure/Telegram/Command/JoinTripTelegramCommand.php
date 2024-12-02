<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Telegram\Command;

use App\Shared\Application\Bus\CommandBusInterface;
use App\TravelExpenseTracker\Application\Command\JoinTrip\JoinTripCommand;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;
use App\TravelExpenseTracker\Domain\ValueObject\ChatMemberUsername;
use App\TravelExpenseTracker\Domain\ValueObject\TravelerFullName;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;
use Twig\Environment;

final class JoinTripTelegramCommand extends AbstractTelegramCommand implements PublicCommandInterface
{
    public const string NAME = 'join-trip';

    public function __construct(
        Environment $twig,
        private readonly CommandBusInterface $commandBus,
    ) {
        parent::__construct($twig);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getAliases(): array
    {
        return [
            '/joinTrip',
            '/jointrip',
        ];
    }

    public function execute(BotApi $api, Update $update): void
    {
        $chat = $update->getMessage()?->getChat();

        $fromUser = $update->getMessage()?->getFrom();
        $username = $fromUser->getUsername();
        $firstName = $fromUser->getFirstName();
        $lastName = $fromUser->getLastName();

        try {
            $command = new JoinTripCommand(
                chatId: new ChatId((string) $chat->getId()),
                chatMemberUsername: new ChatMemberUsername($username),
                travelerFullName: new TravelerFullName($firstName, $lastName),
            );

            $this->commandBus->dispatch($command);
        } catch (\Exception $e) {
            // the main exception class is always the base command error handler,
            // to get rid of the unnecessary wrapper of main exception, we will try
            // to retrieve the previous exception
            $prev = $e->getPrevious() ?? $e;

            $this->sendErrorMessage($api, $chat->getId(), $prev->getMessage());

            return;
        }

        $context = [
            'travelerFullName' => $lastName ? $firstName . ' ' . $lastName : $firstName,
        ];

        $this->sendSuccessMessage($api, $chat->getId(), $context);
    }

    protected function getSuccessMessageTemplate(): string
    {
        return 'tet/telegram/command/join-trip.html.twig';
    }
}

<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Telegram\Command;

use App\Shared\Application\Bus\CommandBusInterface;
use App\TravelExpenseTracker\Application\Command\StartTrip\StartTripCommand;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;
use App\TravelExpenseTracker\Domain\ValueObject\TripTitle;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;
use Twig\Environment;

final class StartTripTelegramCommand extends AbstractTelegramCommand implements PublicCommandInterface
{
    public function __construct(
        Environment $twig,
        private readonly CommandBusInterface $commandBus,
    ) {
        parent::__construct($twig);
    }

    public function getName(): string
    {
        return 'start-trip';
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getAliases(): array
    {
        return [
            '/starttrip',
            '/startTrip',
        ];
    }

    public function execute(BotApi $api, Update $update): void
    {
        $chat = $update->getMessage()?->getChat();

        $tripTitle = $this->getCommandParameters($update) ?? $chat->getTitle();

        try {
            $command = new StartTripCommand(
                new ChatId((string) $chat->getId()),
                new TripTitle($tripTitle)
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

        $this->sendSuccessMessage($api, $chat->getId(), ['tripTitle' => $tripTitle]);
    }

    protected function getSuccessMessageTemplate(): string
    {
        return 'tet/telegram/command/start-trip.html.twig';
    }
}

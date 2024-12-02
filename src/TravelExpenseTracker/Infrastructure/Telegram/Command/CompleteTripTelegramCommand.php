<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Telegram\Command;

use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Application\Bus\QueryBusInterface;
use App\TravelExpenseTracker\Application\Command\CompleteTrip\CompleteTripCommand;
use App\TravelExpenseTracker\Application\Query\GetLastCompletedTripDebts\GetLastCompletedTripDebtsQuery;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;
use Twig\Environment;

final class CompleteTripTelegramCommand extends AbstractTelegramCommand implements PublicCommandInterface
{
    public const string NAME = 'complete-trip';

    public function __construct(
        Environment $twig,
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
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
            '/completetrip',
            '/completeTrip'
        ];
    }

    public function execute(BotApi $api, Update $update): void
    {
        $chat = $update->getMessage()?->getChat();

        try {
            $command = new CompleteTripCommand(
                new ChatId((string) $chat->getId()),
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

        $query = new GetLastCompletedTripDebtsQuery(
            new ChatId((string) $chat->getId())
        );

        $totals = $this->queryBus->ask($query) ?? [];

        $this->sendSuccessMessage($api, $chat->getId(), ['totals' => $totals]);
    }

    protected function getSuccessMessageTemplate(): string
    {
        return 'tet/telegram/command/complete-trip.html.twig';
    }
}

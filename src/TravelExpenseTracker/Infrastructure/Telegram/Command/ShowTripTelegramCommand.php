<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Telegram\Command;

use App\Shared\Application\Bus\QueryBusInterface;
use App\TravelExpenseTracker\Application\DTO\TripData;
use App\TravelExpenseTracker\Application\Query\FindActiveTripByChatId\FindActiveTripByChatIdQuery;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;
use Twig\Environment;

final class ShowTripTelegramCommand extends AbstractTelegramCommand implements PublicCommandInterface
{
    public const string NAME = 'show-trip';

    public function __construct(
        Environment $twig,
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
            '/showtrip',
            '/showTrip',
        ];
    }

    public function execute(BotApi $api, Update $update): void
    {
        $chat = $update->getMessage()?->getChat();

        /** @var TripData|null $tripData */
        $tripData = $this->queryBus->ask(
            new FindActiveTripByChatIdQuery(
                new ChatId((string) $chat->getId())
            )
        );

        if (null === $tripData) {
            $message = 'There is no active trip';

            $this->sendErrorMessage($api, $chat->getId(), $message);

            return;
        }

        $this->sendSuccessMessage($api, $chat->getId(), $tripData->toArray());
    }

    protected function getSuccessMessageTemplate(): string
    {
        return 'tet/telegram/command/show-trip.html.twig';
    }
}

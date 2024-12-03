<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Telegram\Command;

use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use BoShurik\TelegramBotBundle\Telegram\Command\Registry\CommandRegistry;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;
use Twig\Environment;

final class HelpTelegramCommand extends AbstractTelegramCommand implements PublicCommandInterface
{
    public function __construct(
        Environment $twig,
        private readonly CommandRegistry $commandRegistry,
    ) {
        parent::__construct($twig);
    }

    public function getName(): string
    {
        return 'help';
    }

    public function getAliases(): array
    {
        return [
            '/help'
        ];
    }

    public function getDescription(): string
    {
        return 'Displays help information';
    }

    public function getSortOrder(): int
    {
        return 999;
    }

    public function execute(BotApi $api, Update $update): void
    {
        $commands = $this->commandRegistry->getCommands();

        usort($commands, function (AbstractTelegramCommand $a, AbstractTelegramCommand $b): bool {
            return $a->getSortOrder() > $b->getSortOrder();
        });

        $context = ['commands' => []];

        foreach ($commands as $command) {
            if (!$command instanceof PublicCommandInterface) {
                continue;
            }

            if ($command instanceof self) {
                continue;
            }

            $context['commands'][] = [
                'name' => current($command->getAliases()),
                'description' => $command->getDescription(),
                'examples' => $command->getExamples(),
            ];
        }

        $this->sendSuccessMessage(
            $api,
            $update->getMessage()?->getChat()->getId(),
            $context
        );
    }

    protected function getSuccessMessageTemplate(): string
    {
        return 'tet/telegram/command/help.html.twig';
    }
}

<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Telegram\Command;

use BoShurik\TelegramBotBundle\Telegram\Command\CommandInterface;
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
            '/help',
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

        usort($commands, function (CommandInterface $a, CommandInterface $b): int {
            if (!method_exists($a, 'getSortOrder') || !method_exists($b, 'getSortOrder')) {
                return -1;
            }

            return $a->getSortOrder() > $b->getSortOrder() ? 1 : -1;
        });

        $context = ['commands' => []];

        foreach ($commands as $command) {
            if (!$command instanceof PublicCommandInterface) {
                continue;
            }

            // skip "help" command
            if ($command instanceof self) {
                continue;
            }

            $name = method_exists($command, 'getAliases')
                ? current($command->getAliases())
                : $command->getName();

            $examples = method_exists($command, 'getExamples')
                ? $command->getExamples()
                : [];

            $context['commands'][] = [
                'name' => $name,
                'description' => $command->getDescription(),
                'examples' => $examples,
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

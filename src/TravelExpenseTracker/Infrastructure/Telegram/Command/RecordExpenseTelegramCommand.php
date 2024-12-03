<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Telegram\Command;

use App\Shared\Application\Bus\CommandBusInterface;
use App\TravelExpenseTracker\Application\Command\RecordExpense\RecordExpenseCommand;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;
use App\TravelExpenseTracker\Domain\ValueObject\ChatMemberUsername;
use App\TravelExpenseTracker\Domain\ValueObject\ExpenseAmount;
use App\TravelExpenseTracker\Domain\ValueObject\ExpenseDescription;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;
use Twig\Environment;

final class RecordExpenseTelegramCommand extends AbstractTelegramCommand implements PublicCommandInterface
{
    public const string NAME = 'record-expense';

    private const string COMMAND_PARAMETER_SEPARATOR = '/';

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
        return 'Allows to record expenses during active trip';
    }

    public function getAliases(): array
    {
        return [
            '/recordExpense',
            '/recordexpense',
        ];
    }

    public function getExamples(): array
    {
        return [
            '/recordexpense [Amount] / [Description] / [?List of debtors]',
            '/recordexpense 300 / Snacks',
            '/recordexpense 1000 / Dinner / @traveler1 @traveler2',
        ];
    }

    public function getSortOrder(): int
    {
        return 400;
    }

    public function execute(BotApi $api, Update $update): void
    {
        $chat = $update->getMessage()?->getChat();

        $fromUser = $update->getMessage()?->getFrom();
        $payerFirstName = $fromUser->getFirstName();
        $payerLastName = $fromUser->getLastName();
        $payerUsername = $fromUser->getUsername();

        try {
            [$amount, $description, $debtorChatMemberUsernames] = $this->parseCommandParameters($update);

            $debtorChatMemberUsernames = array_map(
                static fn(string $username): ChatMemberUsername => new ChatMemberUsername($username),
                $debtorChatMemberUsernames
            );

            $command = new RecordExpenseCommand(
                chatId: new ChatId((string) $chat->getId()),
                expenseDescription: new ExpenseDescription($description),
                expenseAmount: new ExpenseAmount($amount),
                payerChatMemberUsername: new ChatMemberUsername($payerUsername),
                debtorChatMemberUsernames: $debtorChatMemberUsernames
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
            'amount' => $amount,
            'description' => $description,
            'payerFullName' => $payerLastName ? $payerFirstName . ' ' . $payerLastName : $payerFirstName,
            'debtorCount' => count($debtorChatMemberUsernames),
        ];

        $this->sendSuccessMessage($api, $chat->getId(), $context);
    }

    private function parseCommandParameters(Update $update): array
    {
        $parameters = $this->getCommandParameters($update);
        $parameters = $parameters
            ? explode(self::COMMAND_PARAMETER_SEPARATOR, $parameters)
            : [];

        $requiredParameters = [
            0 => 'Amount (example: 1000)',
            1 => 'Description (example: Bus tickets)',
        ];

        $missedParameters = array_diff_key($requiredParameters, $parameters);

        if (!empty($missedParameters)) {
            $message = sprintf(
                'Please provide the required command arguments separated by the [%s]: %s',
                self::COMMAND_PARAMETER_SEPARATOR,
                implode(', ', $missedParameters),
            );

            throw new \InvalidArgumentException($message);
        }

        $chatMemberUsernamePattern = '/@(\w+)/';

        preg_match_all($chatMemberUsernamePattern, $parameters[2] ?? '', $debtorChatMemberUsernames);

        return [
            trim($parameters[0] ?? ''),
            trim($parameters[1] ?? ''),
            $debtorChatMemberUsernames[1],
        ];
    }

    protected function getSuccessMessageTemplate(): string
    {
        return 'tet/telegram/command/record-expense.html.twig';
    }
}

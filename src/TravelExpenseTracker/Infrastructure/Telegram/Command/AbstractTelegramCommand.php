<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Telegram\Command;

use App\TravelExpenseTracker\Infrastructure\Telegram\Message\MessagePayload;
use BoShurik\TelegramBotBundle\Telegram\Command\AbstractCommand;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use TelegramBot\Api\BotApi;
use Twig\Environment;

abstract class AbstractTelegramCommand extends AbstractCommand implements PublicCommandInterface
{
    public function __construct(
        protected readonly Environment $twig,
    ) {}

    abstract protected function getSuccessMessageTemplate(): string;

    /**
     * @return string[]
     */
    public function getAliases(): array
    {
        return parent::getAliases();
    }

    protected function getErrorMessageTemplate(): string
    {
        return 'tet/telegram/command/error.html.twig';
    }

    protected function sendErrorMessage(BotApi $api, mixed $chatId, ?string $message = null): void
    {
        $context = [
            'description' => $message ?? 'Что-то пошло не так, пожалуйста, попробуйте позже'
        ];

        $payload = new MessagePayload(
            chatId: $chatId,
            template: $this->getErrorMessageTemplate(),
            templateContext: $context
        );

        $this->sendMessage($api, $payload);
    }

    protected function sendSuccessMessage(BotApi $api, mixed $chatId, array $context = []): void
    {
        $payload = new MessagePayload(
            chatId: $chatId,
            template: $this->getSuccessMessageTemplate(),
            templateContext: $context
        );

        $this->sendMessage($api, $payload);
    }

    protected function sendMessage(BotApi $api, MessagePayload $payload): void
    {
        try {
            $text = $this->twig->render($payload->template, $payload->templateContext);
        } catch (\Exception $e) {
            $text = $e->getMessage();
        }

        $api->sendMessage(
            chatId: $payload->chatId,
            text: $text,
            parseMode: $payload->parseMode,
            disablePreview: $payload->disablePreview,
            replyToMessageId: $payload->replyToMessageId,
            replyMarkup: $payload->replyMarkup,
            disableNotification: $payload->disableNotification,
            messageThreadId: $payload->messageThreadId,
            protectContent: $payload->protectContent,
            allowSendingWithoutReply: $payload->allowSendingWithoutReply,
        );
    }
}

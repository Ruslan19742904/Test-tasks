<?php

namespace App\Telegram;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\DTO\Message;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Log;

class CustomWebhookHandler extends WebhookHandler
{
    protected function handleUnknownCommand($text): void
    {
        $this->chat->html("Неизвестная команда: {$text}")->send();
    }

    /**
     * Обработка всех текстовых сообщений (не команд)
     * Правильная сигнатура метода из родительского класса
     */
    protected function handleChatMessage($message): void
    {
        // Сохраняем информацию о чате при любом сообщении
        $this->saveChatInfo();

        $text = $message->text() ?? 'пустое сообщение';
        $this->chat->html("Вы написали: {$text}")->send();
    }

    protected function handleStartCommand(): void
    {
        $this->saveChatInfo(); // Сохраняем информацию о подписчике
        $this->chat->html("Добро пожаловать! Вы подписались на бота.")->send();
    }

    protected function handlePingCommand(): void
    {
        $this->saveChatInfo(); // Сохраняем информацию о подписчике

        $messageText = $this->message?->text() ?? 'no message';

        $chatInfo = [
            'chat_id' => $this->chat->chat_id,
            'username' => $this->getSenderUsername(),
            'first_name' => $this->getSenderFirstName(),
            'last_name' => $this->getSenderLastName(),
            'message' => $messageText,
        ];

        Log::info('Ping command received', $chatInfo);

        $this->chat->html("Pong! Информация о чате записана в лог.")->send();
    }

    private function saveChatInfo(): void
    {
        if (!$this->message) {
            return;
        }

        Subscriber::updateOrCreate(
            [
                'telegraph_bot_id' => $this->bot->id,
                'chat_id' => $this->chat->chat_id,
            ],
            [
                'first_name' => $this->getSenderFirstName(),
                'last_name' => $this->getSenderLastName(),
                'username' => $this->getSenderUsername(),
            ]
        );
    }

    private function getSenderUsername(): ?string
    {
        if (!$this->message) {
            return null;
        }

        $data = $this->message->from();
        return $data['username'] ?? null;
    }

    private function getSenderFirstName(): ?string
    {
        if (!$this->message) {
            return null;
        }

        $data = $this->message->from();
        return $data['first_name'] ?? null;
    }

    private function getSenderLastName(): ?string
    {
        if (!$this->message) {
            return null;
        }

        $data = $this->message->from();
        return $data['last_name'] ?? null;
    }
}

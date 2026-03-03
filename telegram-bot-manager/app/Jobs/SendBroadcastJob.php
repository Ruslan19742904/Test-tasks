<?php

namespace App\Jobs;

use App\Models\Bot;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\UploadedFile;

class SendBroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Bot $bot;
    protected string $message;
    protected ?string $filePath;

    public function __construct(Bot $bot, string $message, ?UploadedFile $file = null)
    {
        $this->bot = $bot;
        $this->message = $message;

        if ($file) {
            $this->filePath = $file->store('broadcasts');
        }
    }

    public function handle(): void
    {
        $subscribers = $this->bot->subscribers;

        foreach ($subscribers as $subscriber) {
            try {
                $message = $this->bot->message($this->message);

                if ($this->filePath) {
                    // Определяем тип файла и отправляем соответствующим методом
                    $mime = mime_content_type(storage_path("app/{$this->filePath}"));

                    if (str_starts_with($mime, 'image/')) {
                        $message->photo(storage_path("app/{$this->filePath}"));
                    } elseif (str_starts_with($mime, 'video/')) {
                        $message->video(storage_path("app/{$this->filePath}"));
                    } elseif (str_starts_with($mime, 'audio/')) {
                        $message->audio(storage_path("app/{$this->filePath}"));
                    } else {
                        $message->document(storage_path("app/{$this->filePath}"));
                    }
                }

                $message->chat($subscriber->chat_id)->send();

            } catch (\Exception $e) {
                \Log::error("Failed to send to subscriber {$subscriber->id}: {$e->getMessage()}");

                if (str_contains($e->getMessage(), 'bot was blocked') ||
                    str_contains($e->getMessage(), 'chat not found')) {
                    $subscriber->delete();
                }
            }
        }

        // Очищаем временный файл
        if ($this->filePath && file_exists(storage_path("app/{$this->filePath}"))) {
            unlink(storage_path("app/{$this->filePath}"));
        }
    }
}

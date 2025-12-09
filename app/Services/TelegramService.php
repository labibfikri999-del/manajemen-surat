<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $botToken;
    protected $apiUrl;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}/sendMessage";
    }

    /**
     * Send a text message to a specific chat ID.
     *
     * @param string $chatId
     * @param string $message
     * @param string|null $parseMode 'Markdown' or 'HTML'
     * @return bool
     */
    public function sendMessage(string $chatId, string $message, string $parseMode = 'Markdown')
    {
        if (!$this->botToken) {
            Log::warning('Telegram Bot Token not configured.');
            return false;
        }

        try {
            $response = Http::post($this->apiUrl, [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => $parseMode,
            ]);

            if ($response->successful()) {
                Log::info("Telegram message sent to {$chatId}");
                return true;
            } else {
                Log::error("Failed to send Telegram message: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Telegram Service Error: " . $e->getMessage());
            return false;
        }
    }
}

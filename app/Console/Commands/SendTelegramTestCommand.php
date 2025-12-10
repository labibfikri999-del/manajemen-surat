<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;

class SendTelegramTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:test {chat_id : Telegram Chat ID to send message to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test message to a Telegram Chat ID to verify connectivity';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegram)
    {
        $chatId = $this->argument('chat_id');
        $this->info("Attempting to send test message to Chat ID: {$chatId}...");
        
        // Cek config
        $token = config('services.telegram.bot_token');
        if (empty($token)) {
            $this->error("ERROR: Telegram Bot Token is missing in config/services.php or .env");
            $this->line("Please check if TELEGRAM_BOT_TOKEN is set in your .env file.");
            return 1;
        }

        $this->line("Using Bot Token: " . substr($token, 0, 5) . str_repeat('*', 10) . substr($token, -5));

        try {
            $success = $telegram->sendMessage($chatId, "*Test Connection*\nThis is a test message from your Laravel application server.");
            
            if ($success) {
                $this->info("✅ Message sent successfully!");
                $this->line("Please check your Telegram client.");
            } else {
                $this->error("❌ Failed to send message.");
                $this->line("Check storage/logs for more details, or ensure the Chat ID is correct and the Bot has been started.");
            }
        } catch (\Exception $e) {
            $this->error("❌ Exception: " . $e->getMessage());
        }
    }
}

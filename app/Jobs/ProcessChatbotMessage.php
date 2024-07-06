<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\ChatbotMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessChatbotMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userMessage;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($userMessage, $userId)
    {
        $this->userMessage = $userMessage;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $venvPython = env('VENV_PATH', '/var/www/venv') . '/bin/python3';
        $result = Process::run("$venvPython /var/www/chatbot.py " . escapeshellarg($this->userMessage));
        $chatBotMessage = $result->output();
        $errorOutput = $result->errorOutput();

        Log::info('userMessage: ' . $this->userMessage);
        Log::info('Chatbot response: ' . $chatBotMessage);
        Log::error('Chatbot error output: ' . $errorOutput);

        ChatbotMessage::create([
            'user_id' => $this->userId,
            'message' => $chatBotMessage,
            'is_chatbot_message' => true,
        ]);

    }
}

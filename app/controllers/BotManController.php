<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

class BotManController
{
    public function handle()
    {
        // Load WebDriver
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
        $config = [];
        $botman = BotManFactory::create($config);

        // Basic "Hello World" example
        $botman->hears('hello', function ($bot) {
            $bot->reply('Hello! How can I help you?');
        });

        // Fallback
        $botman->fallback(function ($bot) {
            $bot->reply("Sorry, I didn't understand that. Type 'hello' to start.");
        });

        $botman->listen();
    }
}

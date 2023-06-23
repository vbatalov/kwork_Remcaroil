<?php

namespace App\Http\Controllers;


use App\Models\Answer;

use Illuminate\Routing\Controller as BaseController;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;
use Throwable;


class Controller extends BaseController
{
    public BotApi $bot;
    public Client $client;
    public TextList $textList;
    public Answer $answer;

    public function __construct()
    {
        $token = env("BOT_API");
        $this->bot = new BotApi("$token", null);
        $this->client = new Client($token, null);

        $this->textList = new TextList();
        $this->answer = new Answer();
    }

    /** Register Bot */
    public function register()
    {

        $page_url1 = env("BOT_URL");
        $page_url2 = "bot";
        $page_url = $page_url1 . $page_url2;


        try {

            if ($this->bot->deleteWebhook()) {
                print_r("Webhook deleted");
            }
            if ($this->bot->setWebhook($page_url)) {
                print_r("\nWebhook set $page_url");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    /** Bot Heart */
    public function index()
    {

        try {
            $commands = new Commands();
            $commands->list();

            $callback_command = new Callbacks();
            $callback_command->callback();

            $messages = new Messages();
            $messages->messagesList();

        } catch (Throwable $e) {
            print_r($e->getMessage());
            $this->bot->sendMessage("112865662", $e->getMessage());
        }
    }
}

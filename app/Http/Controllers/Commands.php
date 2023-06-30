<?php

namespace App\Http\Controllers;

use App\Http\Controllers\bitrix\RESTApi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use TelegramBot\Api\InvalidJsonException;
use TelegramBot\Api\Types\ReplyKeyboardRemove;

class Commands extends Controller
{
    public function list()
    {

        global $bot;
        global $client;

        /**
         * Команда /start
         */

        $this->client->command('start', function ($message) use ($bot) {

            /**
             * Сведения о полученном сообщении
             */
            $cid = $message->getChat()->getId();
            $username = $message->getChat()->getUsername() ?? null;
            $firstname = $message->getChat()->getFirstname() ?? null;
            $lastname = $message->getChat()->getLastname() ?? null;
            $text = $message->getText() ?? null;

            /** @var $user */
            $user = new User();
            /** Проверка пользователя на наличие в БД
             * Если нет, добавить запись
             */
            if (!$user->CheckUserExistInDB($cid)) {
                $user->store("$cid","$username", "$firstname", "$lastname");
            }

            /** Проверка пользователя в БД Битрикс */
            $RESTApi = new RESTApi();
            $RESTApi->checkCIDinContactList($cid);

            // Нулевые куки на случай повторого запуска бота
            $cookie = new Cookie($cid);
            $cookie->setCookie("null");

            $textStart = $this->textList->start();
            $photoURL = env("BOT_URL");
            $photoURL = $photoURL . "storage/bot_start.png";

            $this->bot->sendPhoto("$cid", $photoURL, $textStart["text"], "", $textStart["keyboard"]);
        });

        try {
            if (isset($this->client)) return $this->client->run();
        } catch (InvalidJsonException $e) {
//            return print_r($e->getMessage());
        }
    }

}

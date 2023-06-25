<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;
use Throwable;

class Callbacks extends Controller
{
    public string $cid;
    public string $messageId;
    public string $cbid;
    public string $data;

    public function callback()
    {
        global $bot;

        $this->client->on(function ($update) use ($bot) {

            $callback = $update->getCallbackQuery();
            $message = $callback->getMessage();

            $this->cbid = $callback->getId();
            $this->messageId = $callback->getMessage()->getMessageId();
            $this->data = $callback->getData();
            $this->cid = $message->getChat()->getId();

            /** Список Callback Actions */
            $this->list();

            /** Убираю прогрузку */
            $this->bot->answerCallbackQuery($this->cbid);

        }, function ($update) {
            $callback = $update->getCallbackQuery();
            if (is_null($callback) || !strlen($callback->getData()))
                return false;
            return true;
        });

        // Запуск
        try {
            return $this->client->run();
        } catch (Throwable $e) {
            print_r($e->getMessage());
            $this->bot->sendMessage("112865662", $e->getMessage() . $e->getLine());
        }
    }

    private function list()
    {
        // Если "Уже подписан"
        if ($this->data == "start") {
            return $this->stage2();
        } elseif ($this->data == "stage3") {
            return $this->stage3();
        } elseif ($this->data == "0-100" or $this->data == "100-500" or $this->data == "500+") {
            return $this->stage4($this->data);
        }
    }

    // Вставьте ссылку или картинки
    private function stage2()
    {
        $cookie = new Cookie($this->cid);
        $cookie->setCookie("stage2");

        $text = $this->textList->stage2();
        try {
//            $this->bot->deleteMessage("$this->cid", "$this->messageId");
            return $this->bot->sendMessage("$this->cid", "$text", "HTML");
        } catch (Exception $e) {
        }
    }

    // Какой обьем закупки в шт
    private function stage3()
    {
        $cookie = new Cookie($this->cid);
        $cookie->setCookie("stage3");

        $text = $this->textList->stage3();
        return $this->bot->editMessageText("$this->cid", "$this->messageId", $text['text'], "HTML", "", $text['keyboard']);

    }

    /** Запрос телефона и сохранение объема закупки */
    private function stage4($value)
    {
        /** Сохранить объем закупки в ответы */
        $this->answer->storeValue("$this->cid", "$value");

        $cookie = new Cookie($this->cid);
        $cookie->setCookie("stage4");

        $text = $this->textList->stage4();
        try {
            return $this->bot->sendMessage("$this->cid", $text['text'], "HTML");
        } catch (Exception $e) {
        }
    }


}

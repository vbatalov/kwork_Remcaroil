<?php

namespace App\Http\Controllers;

use App\Http\Controllers\bitrix\RESTApi;
use App\Models\Answer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use TelegramBot\Api\Exception;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\Update;

class Messages extends Controller
{
    public string $cid;
    public mixed $text;

    public function messagesList()
    {

        global $bot;
        global $client;

        /**
         * Начало обработки сообщений
         */

        $this->client->on(function (Update $update) use ($client) {

            $data = $update->getMessage() ?? null;
            if (!empty($data)) {
                $this->cid = $data->getChat()->getId();
                $this->text = $data->getText() ?? null;

                // Модель записи ответов
                $answer = new Answer();

                // Модель куков
                $cookie = new Cookie($this->cid);
                $getCookie = $cookie->getCookie();

                /** Получение ссылки, описание или фото товара */
                if ($getCookie == "stage2") {
                    if ($update->getMessage()->getPhoto()) {
                        $photoCountArrays = count($update->getMessage()->getPhoto()) - 1;
                        $photoId = $update->getMessage()->getPhoto()[$photoCountArrays];
                        $photoId = $photoId->getFileId();
                        $token = env("BOT_API");
                        $urlPhoto = "https://api.telegram.org/bot$token/getFile?file_id=$photoId";
                        if ($jsonDataPhotoUrl = json_decode(file_get_contents($urlPhoto), true)) {
                            $filePath = $jsonDataPhotoUrl["result"]["file_path"]; unset ($jsonDataPhotoUrl);
                            $photoUrl = "https://api.telegram.org/file/bot$token/$filePath";

                            if ($answer->storeImage("$this->cid", "$photoUrl")) {
                                $this->textList->doYouWantContinueToStage3($this->cid);
                            }
                        } else {
                            $this->bot->sendMessage("$this->cid", "Не удалось сохранить ссылку на файл");
                        }

                    } else {
                        if ($answer->storeText("$this->cid", "$this->text")) {
                            $this->textList->doYouWantContinueToStage3($this->cid);
                        }

                    }
                }

                if ($getCookie == "stage4") {
                    if ($answer->storePhone($this->cid, $this->text)) {
                        $cookie->setCookie("stage5");
                        $this->bot->sendMessage("$this->cid", "Укажите Ваше имя");
                    }
                }
                /** Получение имени и конец */
                if ($getCookie == "stage5") {
                    if ($answer->storeName($this->cid, $this->text)) {
                        $cookie->setCookie("end");
                        $bitrix = new RESTApi();
                        $bitrix->storeLead("$this->cid");

                        $this->bot->sendMessage("$this->cid", "Отлично! Я свяжусь с тобой в течении 6 часов и предложу самые лучшие варианты. \n\nНе забудь подписаться на мой инст там много интересного про закупку в Китае \nhttps://instagram.com/anton_krasilnlkov");
                    }
                }

                if ($getCookie == "end") {
                    $this->bot->sendMessage("$this->cid", "Вы уже отправили заявку, если хотите отправить ещё, нажмите /start и следуйте инструкциям.");
                }
            }

        }, function () {
            return true; // когда тут true - команда проходит
        });

        return $this->client->run();

    }

}

<?php

namespace App\Http\Controllers;

use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class TextList
{
    public function start() {

        $keyboard = new InlineKeyboardMarkup (
            [
                [
                    ['url' => env("BOT_TG_CHANNEL_URL"), 'text' => 'Подписаться'],
                ],
                [
                    ['callback_data' => 'start', 'text' => 'Уже подписан'],
                ],
            ]
        );

        $text = "
        Нихао !, рад знакомству, меня зовут Антон Красильников ! И я уже более 10 лет помогаю предпринимателям зарабатывать кратно больше благодаря закупкам товара из Китая по оптовым ценам. Обязательно подпишись на мой телеграм, там я рассказываю про Китай, Маркетплейсы, Оффлайн и я задам тебе несколько вопросов о закупке
       ";

        return ["text" => $text, "keyboard" => $keyboard];
    }

    public function stage2() {

        $text = "
        Вставьте ссылку или картинки или описание товара по которому будем оптимизироваться
       ";

        return $text;
    }

    public function stage3() {
        $keyboard = new InlineKeyboardMarkup (
            [
                [
                    ['callback_data' => '0-100', 'text' => '0-100'],
                ],
                [
                    ['callback_data' => '100-500', 'text' => '100-500'],
                ],
                [
                    ['callback_data' => '500+', 'text' => 'от 500'],
                ],
            ]
        );

        $text = "
        Укажите объем закупки в штуках  
        ";

        return ["text" => $text, "keyboard" => $keyboard];
    }

    public function doYouWantContinueToStage3($cid) {
        $keyboard = new InlineKeyboardMarkup (
            [
                [
                    ['callback_data' => 'stage3', 'text' => 'Продолжить'],
                ],
            ]
        );

        try {
            $bot = new Controller();
            return $bot->bot->sendMessage("$cid", "<b>Информация получена</b> \n\nВы хотите добавить ещё ссылки, описание или картинки? \n\nПросто отправьте ещё информацию или нажмите кнопку <b>Продолжить</b>, чтобы перейти в следующее меню.", "HTML","", "", $keyboard);

        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    /** Запрос телефона */
    public function stage4() {
        $text = "
        Укажите Ваш номер для связи
        ";

        return ["text" => $text];
    }
}

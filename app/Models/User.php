<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    /** Проверка пользователя в БД */
    public function CheckUserExistInDB($cid)
    {
        if (User::where("cid", $cid)->exists()) {
            return true;
        }
        return false;
    }

    /** Добавить пользователя */
    public function store($cid, $username, $firstname, $lastname)
    {
        $user = new User();
        $user->cid = $cid;
        $user->username = $username;
        $user->first_name = $firstname;
        $user->last_name = $lastname;
        return $user->save();
    }

    public function getUser($cid)
    {
        if (!User::where("cid", $cid)->exists()) {
            return false;
        }
        $collect = collect();

        $user = User::where("cid", $cid)->first();
        $collect->put("first_name", $user->first_name);
        $collect->put("last_name", $user->last_name);
        $collect->put("cid", $user->cid);
        $collect->put("phone", $user->phone);


        return $collect;
    }

    public function getAnswers($cid) {
        $answers = Answer::where("cid", $cid)->get()->all();
        $answersText = "";

        foreach ($answers as $answer) {
            $type = match ($answer->type) {
                "text" => "Ссылка / Описание",
                "image" => "Картинка",
                "value" => "Объем",
                default => "N/A",
            };

            $answersText .= ("$type: $answer->data \n");
        }

        return $answersText;
    }

    // Обновление Битрикс ID (контакта) для пользователя Телеграм
    public function updateBitrixIdForUser($cid, $bitrix_id) {
        $user = User::where("cid", $cid)->get()->first();
        $user->bitrix_id = $bitrix_id;
        return $user->save();
    }

    /** Возвращает ID пользователя в Битрикс из локальной БД */
    public function getBitrixId($cid) {
        $user = User::where("cid", $cid)->first();
        return $user->bitrix_id;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    public function storeText($cid, $text): bool
    {
        $answer = new Answer();
        $answer->cid = $cid;
        $answer->type = "text";
        $answer->data = $text;
        $answer->save();

        return true;
    }

    public function getImage($cid)
    {
        $items = Answer::where("cid", $cid)->where("type", "image")->get("data")->all();

        $array = [];
        foreach ($items as $key => $item) {
            $array [$key] = $item->data;
        }
        return $array;
    }

    public function storeImage($cid, $url): bool
    {
        $answer = new Answer();
        $answer->cid = $cid;
        $answer->type = "image";
        $answer->data = $url;
        $answer->save();

        return true;
    }

    public function getValue($cid)
    {
        $items = Answer::where("cid", $cid)->where("type", "value")->get("data")->all();

        $array = [];
        foreach ($items as $key => $item) {
            $array [$key] = $item->data;
        }
        return $array;
    }

    public function storeValue($cid, $value): bool
    {
        $answer = new Answer();
        $answer->cid = $cid;
        $answer->type = "value";
        $answer->data = $value;
        $answer->save();

        return true;
    }

    public function storePhone($cid, $phone): bool
    {
        $user = User::where("cid", $cid)->first();
        $user->phone = $phone;
        $user->save();

        return true;
    }

    public function storeName($cid, $name): bool
    {
        $user = User::where("cid", $cid)->first();
        $user->first_name = $name;
        $user->save();

        return true;
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Cookie extends Controller
{
    public string $cid;

    public function __construct($cid)
    {
        $this->cid = $cid;
    }

    // Установка куков для отслеживания сообщений
    public function setCookie($value) {
        $user = User::where("cid", $this->cid)->first();
        $user->cookie = $value;

        return $user->save();
    }

    public function getCookie() {
        $user = User::where("cid", $this->cid)->first();
        return $user->cookie;
    }
}

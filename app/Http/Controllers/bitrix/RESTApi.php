<?php

namespace App\Http\Controllers\bitrix;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\User;
use CRest;
use Illuminate\Http\Request;

require_once(__DIR__ . '/crest.php');

class RESTApi extends Controller
{
    public function store($cid)
    {

    }


    public function checkCIDinContactList($cid)
    {
        $contact_list = CRest::call(
            'crm.contact.list',
            ['FILTER' => ['UF_CRM_1687440612131' => "$cid",], 'SELECT' => ['0' => 'ID',],]
        );


        // Если пользователя нет в списке Контактов
        // return contactAdd()
        if (!isset($contact_list["result"]["0"]["ID"])) {
            return $this->contactAdd($cid);
        } else {
            // Если такой пользователь уже есть в Битрикс
            $bitrix_id = $contact_list["result"]["0"]["ID"];
            $user = new User();
            return $user->updateBitrixIdForUser("$cid", "$bitrix_id");
        }

    }

    /**
     * В методе crm.contact.add подставляешь значние только полей, остальное статика:
     * - NAME
     * - LAST_NAME
     * - UF_CRM_1687440612131 (ТГ-Бот (client ID))
     * - EMAIL
     * - PHONE
     */
    public function contactAdd($cid)
    {
        $user = new User();

        // Коллекция информации о пользователе
        // Далее отправляем вебхук для создания контакта в Битрикс
        // Полученный ID заносим в БД
        if ($getUser = $user->getUser($cid)) {
            $contact_add = CRest::call(
                'crm.contact.add',
                ['FIELDS' =>
                    [
                        'NAME' => $getUser->get("first_name"),
                        'LAST_NAME' => $getUser->get("last_name"),
                        'SOURCE_ID' => '1', 'ASSIGNED_BY_ID' => '1',
                        'UF_CRM_1687440612131' => $getUser->get("cid"),
                        'EMAIL' => ['0' => ['VALUE' => 'mail@example.com', 'VALUE_TYPE' => 'WORK',],],
                        'PHONE' => ['0' => [
                            'VALUE' => $getUser->get("phone"), 'VALUE_TYPE' => 'WORK',],
                            ],
                        ],
                    ]
            );

            // Получение ID для нового контакта и обновление в БД для пользователя
            $bitrix_id = $contact_add['result'];
            return $user->updateBitrixIdForUser($cid, $bitrix_id);
        }
    }

    /** Создание лида в Битрикс */
    // CONTACT_ID - созданный или найденный контакт
    // UF_CRM_1687444753246 - количество товара
    // UF_CRM_1687444776702 - ссылка на товар
    // Поля множественные, туда можно пихать массив данных через указание ключа массива
    public function storeLead($cid)
    {
        $this->checkCIDinContactList($cid);

        $user = new User();
        $answer = new Answer();

        $result = CRest::call(
            'crm.deal.add',
            ['FIELDS' =>
                [
                    'CONTACT_ID' => $user->getBitrixId($cid),
                    'UF_CRM_1687444753246' => $answer->getValue($cid), // UF_CRM_1687444753246 - количество товара
                    'UF_CRM_1687444776702' => $answer->getAnswers($cid), // UF_CRM_1687444776702 - ссылка на товар
                ],
            ]
        );

        /** Удаление всех ответов пользователя после создания Лида в Битрикс */
        if ($result) {
            Answer::where('cid', "$cid")->delete();
        }
        return print_r($result);
    }
}

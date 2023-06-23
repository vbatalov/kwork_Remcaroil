<?php

namespace App\Http\Controllers\bitrix;

use App\Http\Controllers\Controller;
use App\Models\User;
use CRest;
use Illuminate\Http\Request;

require_once(__DIR__ . '/crest.php');

class RESTApi extends Controller
{
    public function store($cid) {

    }
    public function checkCIDinContactList($cid = null)
    {
        $cid = 112865662;
        $contact_list = CRest::call(
            'crm.contact.list',
            ['FILTER' => ['UF_CRM_1687440612131' => "$cid",], 'SELECT' => ['0' => 'ID',],]
        );


        // Если пользователя нет в списке Контактов
        // return contactAdd()
        if (!isset($contact_list["result"]["0"]["ID"])) {
            return $this->contactAdd($cid);
        } else {
            dd("yes", $contact_list);

        }
//        if ($contact_id == null) {
//            $contact_id = $this->contactAdd($cid);
//        }
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
        $getUser = $user->getUser($cid); // Коллекция информации о пользователе

//        $contact_add = CRest::call(
//            'crm.contact.add',
//            ['FIELDS' => ['NAME' => 'Иван', 'LAST_NAME' => 'Петров', 'SOURCE_ID' => '1', 'ASSIGNED_BY_ID' => '1', 'UF_CRM_1687440612131' => '1234', 'EMAIL' => ['0' => ['VALUE' => 'mail@example.com', 'VALUE_TYPE' => 'WORK',],], 'PHONE' => ['0' => ['VALUE' => '79998889988', 'VALUE_TYPE' => 'WORK',],],],]
//        );

//        return $contact_add["result"];
    }
}

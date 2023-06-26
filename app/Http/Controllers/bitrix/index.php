<?php
require_once (__DIR__.'/crest.php');

/** ФАЙЛ НЕ ИСПОЛЬЗУЕТСЯ */
/** Носит только информационный характер */

// Прежде чем создавать клиента, сначала надо проверить его существование в CRM. Методом crm.contact.list получаем клиента, у которого заполнено поле
// "ТГ-Бот (client ID)", далее по условию понятно, что если нет такого, то создаем новый. 
// Обрати внимание, что UF_CRM_1687440612131 - это айди поля "ТГ-Бот (client ID), то есть при создании клиента методом crm.contact.add ты должен в это поле положить значение из БД, которое будет равно ID пользователя в ТГ боте.
// Это нужно для того, чтобы каждая заявка с бота не создавала одного и того же клиента дублями в СРМ

$contact_list = CRest::call(
		'crm.contact.list',
		['FILTER' => ['UF_CRM_1687440612131' => '21', ], 'SELECT' => ['0' => 'ID', ], ]
	);

$contact_id = $contact_list["result"]["0"]["ID"];

if ($contact_id == null)
{
// В методе crm.contact.add подставляешь значние только полей, остальное статика:
//     - NAME
//     - LAST_NAME
//     - UF_CRM_1687440612131 (ТГ-Бот (client ID))
//     - EMAIL
//     - PHONE

  $contact_add = CRest::call(
		'crm.contact.add',
		['FIELDS' => ['NAME' => 'Иван', 'LAST_NAME' => 'Петров', 'SOURCE_ID' => '1', 'ASSIGNED_BY_ID' => '1', 'UF_CRM_1687440612131' => '1234', 'EMAIL' => ['0' => ['VALUE' => 'mail@example.com', 'VALUE_TYPE' => 'WORK', ], ], 'PHONE' => ['0' => ['VALUE' => '79998889988', 'VALUE_TYPE' => 'WORK', ], ], ], ]
	);
	
	$contact_id = $contact_add["result"];
}


//// Создаем сделку методом crm.deal.add
// Где:
// CONTACT_ID - созданный или найденный контакт
// UF_CRM_1687444753246 - количество товара
// UF_CRM_1687444776702 - ссылка на товар
// Поля множественные, туда можно пихать массив данных через указание ключа массива

$deal_add = CRest::call(
		'crm.deal.add',
		['FIELDS' => ['CONTACT_ID' => $contact_id, 'UF_CRM_1687444753246' => ['0' => '5000', ], 'UF_CRM_1687444776702' => ['0' => 'https://www.google.ru/', ], ], ]
	);

// echo '<pre>';
// 	print_r($deal_add);
// echo '</pre>';
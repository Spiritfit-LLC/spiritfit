<?php

class DefaultConversion{
    // накрутка наших счетчиков
    static function addAuthCounters()
    {
        if (Bitrix\Main\Loader::includeModule('conversion'))
        {
            $context = Bitrix\Conversion\DayContext::getInstance();
            $context->addDayCounter('auth_day', 1);
            $context->addCounter('auth_all', 1);
        }
    }

    // информация о наших счетчиках для модуля Конверсия
    static function getCounterTypes()
    {
        return array(
            'auth_day' => array('MODULE' => 'auth', 'NAME' => 'Количество авторизаций на сайте в день', 'GROUP' => 'day'),
            'auth_all' => array('MODULE' => 'auth', 'NAME' => 'Общее количество авторизаций на сайте'),
        );
    }

    // информация для вычисления конверсии
    static function getRateTypes()
    {
        return array(
            'auth' => array(
                'NAME'      => 'Количество авторизаций на сайте',
                'MODULE'    => 'auth',
                'SORT'      => 10, // порядок отображения конверсии
                'SCALE'     => array(0.5, 1, 1.5, 2, 5), // шкала: Плохо 0% - Отлично 5%

                // счетчики которые будут переданы в функцию вычисления конверсии
                'COUNTERS'  => array('conversion_visit_day', 'auth_day', 'auth_all'),

                // функция вычисления конверсии
                'CALCULATE' => function (array $counters)
                {
                    $denominator = $counters['conversion_visit_day'] ?: 0; // знаменатель
                    $numerator   = $counters['auth_day'] ?: 0; // числитель
                    $quantity    = $counters['auth_all'] ?: 0;

                    return array(
                        'DENOMINATOR' => $denominator,
                        'NUMERATOR'   => $numerator,
                        'QUANTITY'    => $quantity,
                        'RATE'        => $denominator ? $numerator / $denominator : 0, // формула конверсии
                    );
                },
            ),
        );
    }
}


$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler('conversion', 'OnGetCounterTypes', array('DefaultConversion', 'getCounterTypes'));
$eventManager->addEventHandler('conversion', 'OnGetRateTypes'   , array('DefaultConversion', 'getRateTypes'));


AddEventHandler("main", "OnAfterUserAuthorize", Array("AuthEventsClass", "OnAfterUserAuthorizeHandler"));
AddEventHandler("main", "OnAfterUserLogin", Array("MyClass", "OnAfterUserLoginHandler"));

class AuthEventsClass{
    function OnAfterUserAuthorizeHandler($arUser)
    {
        DefaultConversion::addAuthCounters();
    }
    function OnAfterUserLoginHandler(&$fields)
    {
        DefaultConversion::addAuthCounters();
    }
}
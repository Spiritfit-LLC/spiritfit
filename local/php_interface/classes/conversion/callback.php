<?php

class CallbackConversion{
    // накрутка наших счетчиков
    static function addCounters()
    {
        if (Bitrix\Main\Loader::includeModule('conversion'))
        {
            $context = Bitrix\Conversion\DayContext::getInstance();
            $context->addDayCounter('callback_day', 1);
            $context->addCounter('callback_all', 1);
        }
    }

    // информация о наших счетчиках для модуля Конверсия
    static function getCounterTypes()
    {
        return array(
            'callback_day' => array('MODULE' => 'callback', 'NAME' => 'Количество заявок на обратный звонок в день', 'GROUP' => 'day'),
            'callback_all' => array('MODULE' => 'callback', 'NAME' => 'Общее количество заявок на обратный звонок'),
        );
    }

    // информация для вычисления конверсии
    static function getRateTypes()
    {
        return array(
            'callback' => array(
                'NAME'      => 'Количество заявок обратного звонка',
                'MODULE'    => 'callback',
                'SORT'      => 100, // порядок отображения конверсии
                'SCALE'     => array(0.5, 1, 1.5, 2, 5), // шкала: Плохо 0% - Отлично 5%

                // счетчики которые будут переданы в функцию вычисления конверсии
                'COUNTERS'  => array('conversion_visit_day', 'callback_day', 'callback_all'),

                // функция вычисления конверсии
                'CALCULATE' => function (array $counters)
                {
                    $denominator = $counters['conversion_visit_day'] ?: 0; // знаменатель
                    $numerator   = $counters['callback_day'] ?: 0; // числитель
                    $quantity    = $counters['callback_all'] ?: 0;

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
$eventManager->addEventHandler('conversion', 'OnGetCounterTypes', array('CallbackConversion', 'getCounterTypes'));
$eventManager->addEventHandler('conversion', 'OnGetRateTypes'   , array('CallbackConversion', 'getRateTypes'   ));
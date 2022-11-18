<?php


class QuizConversion{
    static function addCounters()
    {
        if (Bitrix\Main\Loader::includeModule('conversion'))
        {
            $context = Bitrix\Conversion\DayContext::getInstance();
            $context->addDayCounter('quiz_day', 1);
            $context->addCounter('quiz_all', 1);
        }
    }

    // информация о наших счетчиках для модуля Конверсия
    static function getCounterTypes()
    {
        return array(
            'quiz_day' => array('MODULE' => 'quiz', 'NAME' => 'Количество кликов на кнопку квиз в ЛК', 'GROUP' => 'day'),
            'quiz_all' => array('MODULE' => 'quiz', 'NAME' => 'Общее количество кликов на кнопку квиз в ЛК'),
        );
    }

    // информация для вычисления конверсии
    static function getRateTypes()
    {
        return array(
            'quiz' => array(
                'NAME'      => 'Количество кликов на кнопку квиз в ЛК',
                'MODULE'    => 'quiz',
                'SORT'      => 200, // порядок отображения конверсии
                'SCALE'     => array(0.5, 1, 1.5, 2, 5), // шкала: Плохо 0% - Отлично 5%

                // счетчики которые будут переданы в функцию вычисления конверсии
                'COUNTERS'  => array('auth_day', 'quiz_day', 'quiz_all'),

                // функция вычисления конверсии
                'CALCULATE' => function (array $counters)
                {
                    $denominator = $counters['auth_day'] ?: 0; // знаменатель
                    $numerator   = $counters['quiz_day'] ?: 0; // числитель
                    $quantity    = $counters['quiz_all'] ?: 0;

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
$eventManager->addEventHandler('conversion', 'OnGetCounterTypes', array('QuizConversion', 'getCounterTypes'));
$eventManager->addEventHandler('conversion', 'OnGetRateTypes'   , array('QuizConversion', 'getRateTypes'   ));
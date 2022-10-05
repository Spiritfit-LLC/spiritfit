<?php

class SliderBtnConversion{
    // накрутка счетчиков
    static function addCounters()
    {
        if (Bitrix\Main\Loader::includeModule('conversion'))
        {
            $context = Bitrix\Conversion\DayContext::getInstance();
            $context->addDayCounter('slider_click_day', 1);
            $context->addCounter('slider_click_all', 1);
        }
    }

    // информация о наших счетчиках для модуля Конверсия
    static function getCounterTypes()
    {
        return array(
            'slider_click_day' => array('MODULE' => 'slider.btn', 'NAME' => 'Количество кликов по кнопке слайдера акций', 'GROUP' => 'day'),
            'slider_click_all' => array('MODULE' => 'slider.btn', 'NAME' => 'Общее количество кликов по кнопке слайдера акций'),
        );
    }

    // информация для вычисления конверсии
    static function getRateTypes()
    {
        return array(
            'slider.btn' => array(
                'NAME'      => 'Количество кликов по кнопке слайдера акций',
                'MODULE'    => 'slider.btn',
                'SORT'      => 100, // порядок отображения конверсии
                'SCALE'     => array(0.5, 1, 1.5, 2, 5), // шкала: Плохо 0% - Отлично 5%

                // счетчики которые будут переданы в функцию вычисления конверсии
                'COUNTERS'  => array('conversion_visit_day', 'slider_click_day', 'slider_click_all'),

                // функция вычисления конверсии
                'CALCULATE' => function (array $counters)
                {
                    $denominator = $counters['conversion_visit_day'] ?: 0; // знаменатель
                    $numerator   = $counters['slider_click_day'] ?: 0; // числитель
                    $quantity    = $counters['slider_click_all'] ?: 0;

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
$eventManager->addEventHandler('conversion', 'OnGetCounterTypes', array('SliderBtnConversion', 'getCounterTypes'));
$eventManager->addEventHandler('conversion', 'OnGetRateTypes'   , array('SliderBtnConversion', 'getRateTypes'   ));
<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = array(
    // группы в левой части окна
    "GROUPS" => [
        "SETTINGS" => [
            "NAME" => "Настройка компонента",
            "SORT" => 10,
        ],
    ],
    // поля для ввода параметров в правой части
    "PARAMETERS" => [
        // Произвольный параметр типа СПИСОК
        "PROMOCODE" => [
            "PARENT" => "SETTINGS",
            "NAME" => "ПРОМОКОД",
            "TYPE" => "STRING",
            "ADDITIONAL_VALUES" => "Y",
            "MULTIPLE"=>"N"
        ],
        "BANNER_DISCOUNT"=>[
            "PARENT" => "SETTINGS",
            "NAME" => "СКИДКА",
            "TYPE" => "STRING",
            "ADDITIONAL_VALUES" => "Y",
            "MULTIPLE"=>"N"
        ],
        "BANNER_TIME"=>[
            "PARENT" => "SETTINGS",
            "NAME" => "Время до показа в миллисекундах",
            "TYPE" => "INT",
            "ADDITIONAL_VALUES" => "Y",
            "MULTIPLE"=>"N"
        ],
    ]
);
?>
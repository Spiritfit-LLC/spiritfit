<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$ext = 'jpg,jpeg,png';
$methods_1c=["phone"=>"Заявка на обратный звонок (баннер)"];

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
        "URL" => [
            "PARENT" => "SETTINGS",
            "NAME" => "Страницы показа",
            "TYPE" => "STRING",
            "ADDITIONAL_VALUES" => "Y",
            "MULTIPLE"=>"Y"
        ],
        "BACKGROUND"=>[
            "PARENT" => "SETTINGS",
            "NAME" => 'Выберите фон баннера (если нет, то currentColor)',
            "TYPE" => "FILE",
            "FD_TARGET" => "F",
            "FD_EXT" => $ext,
            "FD_UPLOAD" => true,
            "FD_USE_MEDIALIB" => true,
            "FD_MEDIALIB_TYPES" => Array('image')
        ],
        "FORM_TYPE"=>[
            "PARENT" => "SETTINGS",
            "NAME" => "Тип формы",
            "TYPE" => "STRING",
            "ADDITIONAL_VALUES" => "Y",
            "MULTIPLE"=>"N"
        ],
        "1C_ACTION"=>[
            "PARENT" => "SETTINGS",
            "NAME" => "Метод 1с",
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "MULTIPLE"=>"N"
        ]
    ]
);
?>
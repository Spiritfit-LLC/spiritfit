<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


$arComponentParameters = array(
    "GROUPS"=>array(
        "PERSONAL_SETTINGS"=>array(
            "SORT"=>100,
            "NAME"=>"Настройка компонента \"Личный кабинет\""
        )
    ),
    "PARAMETERS"=>array(
        "SEF_MODE" => Array(
            "me" => array(
                "NAME" => "Главная страница ЛК",
                "DEFAULT" => "",
                "VARIABLES" => array(),
            ),
            "services" => array(
                "NAME" => "Страница услуг",
                "DEFAULT" => "services/",
                "VARIABLES" => array(),
            ),
            "settings" => array(
                "NAME" => "Страница настроек",
                "DEFAULT" => "settings/",
                "VARIABLES" => array(),
            ),
            "loyalty" => array(
                "NAME" => "Страница программы лоялности",
                "DEFAULT" => "loyalty/",
                "VARIABLES" => array(),
            ),
        ),
        "AJAX_MODE" => array(),
        "SET_TITLE" => Array(),
        "CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
    )
);
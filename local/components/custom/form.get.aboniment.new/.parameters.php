<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("form")) return;

$arrForms = array();
$rsForm = CForm::GetList($by='s_sort', $order='asc', array("SITE" => $_REQUEST["site"]), $v3);
while ($arForm = $rsForm->Fetch())
{
    $arrForms[$arForm["CODE"]] = "[".$arForm["CODE"]."] ".$arForm["NAME"];
}

$form_actions=[
    "getAbonement"=>"[getAbonement] Оплата абонемента",
    "getTrial"=>"[getTrial] Пробная тренировка"
];

$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "WEB_FORM_ID" => array(
            "NAME" => "ID WEB-формы",
            "TYPE" => "LIST",
            "VALUES" => $arrForms,
            "ADDITIONAL_VALUES"	=> "Y",
            "PARENT" => "DATA_SOURCE",
        ),
        "ELEMENT_CODE" => Array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Символьный код элемента абонемента",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ),
        "CLUB_ID" => Array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => "ID клуба",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ),
        "FORM_TYPE" => Array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Тип события формы в 1С",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ),
        "FORM_ACTION" => array(
            "NAME" => "Событие формы",
            "TYPE" => "LIST",
            "VALUES" => $form_actions,
            "ADDITIONAL_VALUES"	=> "Y",
            "PARENT" => "DATA_SOURCE",
        ),
    ),
);
?>
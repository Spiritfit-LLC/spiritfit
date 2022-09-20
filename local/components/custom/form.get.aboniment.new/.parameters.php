<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("form")) return;
if (!CModule::IncludeModule("iblock")) return;

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

$leaders=[
    "-"=>"[-] Не выводить",
	"0"=>"[0] Выводить список"
];
$leadersIblockId=Utils::GetIBlockIDBySID('leaders');
if(!empty($leadersIblockId)){
    $dbElements = CIBlockElement::GetList(array('NAME' => 'ASC'), ['IBLOCK_ID' => $leadersIblockId, 'ACTIVE' => 'Y'], false, false, array("ID", "CODE", "NAME"));
    while ($arFields = $dbElements->fetch()) {
        $leaders[$arFields['ID']] = sprintf('[%d] Выводить список (По умолчанию - %s)', $arFields['ID'], $arFields['NAME']);
	}
}

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
		"FORM_ACTION" => array(
            "NAME" => "Событие формы",
            "TYPE" => "LIST",
            "VALUES" => $form_actions,
            "ADDITIONAL_VALUES"	=> "Y",
            "PARENT" => "DATA_SOURCE",
        ),
		"SELECTED_LEADER_ID" => array(
            "NAME" => "Cписок для выбора тренера",
            "TYPE" => "LIST",
            "VALUES" => $leaders,
            "ADDITIONAL_VALUES"	=> "Y",
			"MULTIPLE" => "N",
            "PARENT" => "DATA_SOURCE",
        ),
    ),
);
?>
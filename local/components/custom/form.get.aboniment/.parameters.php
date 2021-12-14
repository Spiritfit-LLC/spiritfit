<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("form")) return;

$arrForms = array();
$rsForm = CForm::GetList($by='s_sort', $order='asc', array("SITE" => $_REQUEST["site"]), $v3);
while ($arForm = $rsForm->Fetch())
{
	$arrForms[$arForm["ID"]] = "[".$arForm["ID"]."] ".$arForm["NAME"];
}

$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"WEB_FORM_ID" => array(
			"NAME" => "ID WEB-формы",
			"TYPE" => "LIST",
			"VALUES" => $arrForms,
			"ADDITIONAL_VALUES"	=> "Y",
			"DEFAULT" => "={\$_REQUEST[WEB_FORM_ID]}",
			"PARENT" => "DATA_SOURCE",
		),
		"ABONEMENT_IBLOCK_ID" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => "ID инфоблока с абонементами",
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
		),
		"CLUBS_IBLOCK_ID" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => "ID инфоблока с клубами",
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
		),
		"ELEMENT_CODE" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => "Символьный код элемента клуба",
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
		"DEFAULT_CLUB_ID" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => "Номер клуба по умолчанию",
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
		),
		"FREE_MESSAGE" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => "Сообщение о бесплатном абонементе",
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
		)
	),
);
?>
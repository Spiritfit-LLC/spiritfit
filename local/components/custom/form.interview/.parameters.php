<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('form');

$arrForms = array();
$rsForm = CForm::GetList($by='s_sort', $order='asc', array("SITE" => $_REQUEST["site"]), $v3);
while ($arForm = $rsForm->Fetch())
{
	$arrForms[$arForm["ID"]] = "[".$arForm["ID"]."] ".$arForm["NAME"];
}

$arTypesEx = CIBlockParameters::GetIBlockTypes(array("-"=>" "));

$arIBlocks = array();
$db_iblock = CIBlock::GetList(array("SORT"=>"ASC"), array("TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch()) {
	$arIBlocks[$arRes["CODE"]] = "[".$arRes["ID"]."] ".$arRes["NAME"];
}

$arComponentParameters = array(
	"GROUPS" => array(

	),
	"PARAMETERS" => array(
		"WEB_FORM_ID" => array(
			"NAME" => "ID WEB-формы",
			"TYPE" => "LIST",
			"VALUES" => $arrForms,
			"ADDITIONAL_VALUES"	=> "Y",
			"DEFAULT" => "={\$_REQUEST[WEB_FORM_ID]}",
			"PARENT" => "DATA_SOURCE",
		),
		"EXCEL_FILE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => "Имя файла EXCEL",
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"IBLOCK_CODE" => array(
			"PARENT" => "BASE",
			"NAME" => "Инфоблок с клубами",
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '',
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
        ),
	),
);
?>
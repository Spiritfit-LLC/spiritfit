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
		"CLIENT_TYPE"=>array(
			"NAME"=>"Тип для UPMETRIKA",
			"DEFAULT" => NULL,
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"COLS" => 25,
			"REFRESH"=> "N",
		),
        "INCLUDE_CLUB_FIELD"=>array(
            "NAME"=>"Включить поле выбора клуба?",
            "DEFAULT"=>"N",
            "TYPE"=>"CHECKBOX",
            "MULTIPLE" => "N",
            "PARENT" => "DATA_SOURCE",
        )
	),
);
?>
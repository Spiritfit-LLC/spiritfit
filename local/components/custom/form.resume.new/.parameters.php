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
        "EMAIL"=>array(
            "NAME"=>"Email адрес",
            "TYPE"=>"STRING",
            "DEFAULT"=>"",
            "PARENT" => "DATA_SOURCE",
        )
    ),
);
?>
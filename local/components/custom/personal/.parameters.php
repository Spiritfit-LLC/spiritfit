<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

if (!CModule::IncludeModule("form")) return;

$arrForms = array();
$rsForm = CForm::GetList($by='s_sort', $order='asc', array("SITE" => $_REQUEST["site"]), $v3);
while ($arForm = $rsForm->Fetch())
{
    $arrForms[$arForm["CODE"]] = "[".$arForm["CODE"]."] ".$arForm["NAME"];
}

$arComponentParameters = array(
    "PARAMETERS" => array(
        "AUTH_FORM_CODE"=>array(
            "NAME"=>"CODE Формы авторизации",
            "TYPE" => "LIST",
            "VALUES" => $arrForms,
        ),
        "REGISTER_FORM_CODE"=>array(
            "NAME"=>"CODE Формы регистрации",
            "TYPE" => "LIST",
            "VALUES" => $arrForms,
        ),
        "PASSFORGOT_FORM_CODE"=>array(
            "NAME"=>"CODE Формы для восстановления пароля",
            "TYPE" => "LIST",
            "VALUES" => $arrForms,
        ),
        "ACTIVE_FORM"=>array(
            'NAME'=>"Активная форма после авторизации",
            "TYPE"=>"STRING",
            "DEFAULT"=>""
        ),
        "SHOW_ERRORS" => array(
            "NAME" => GetMessage("COMP_AUTH_FORM_SHOW_ERRORS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
        ),
    ),
);

<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once 'PersonalUtils.php';
use PersonalUtils;
use \Bitrix\Main\Loader;

Loader::IncludeModule("form");
Loader::IncludeModule("iblock");



$auth_form_id=PersonalUtils::GetIDBySID($arParams['AUTH_FORM_CODE']);
$reg_form_id=PersonalUtils::GetIDBySID($arParams['REGISTER_FORM_CODE']);
$passforgot_form_id=PersonalUtils::GetIDBySID($arParams['PASSFORGOT_FORM_CODE']);

if (empty($auth_form_id) ||
    empty($reg_form_id) ||
    empty($passforgot_form_id))
{
    return;
}



global $USER;
global $APPLICATION;


$url =$_SERVER['REQUEST_URI'];
$parts = parse_url($url);
$currentUrl = $parts['path'];

parse_str($parts['query'], $query);
if (isset($query['reg'])){
    $active='reg';
}
elseif (isset($query['forgot'])){
    $active='forgot';
}
else{
    $active='auth';
}

$arResult["AUTH"]=$USER->IsAuthorized();
$arResult["PROFILE_URL"] = $arParams["PROFILE_URL"];
$arResult['ajax']=str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__).'/ajax.php';

$arResult['PROFILE_PAGE']=defined('PERSONAL_PAGE');



//НА ВРЕМЯ ТЕСТА ПОКАЗЫВАЕМ ТОЛЬКО НА СТРАНИЦЕ PERSONAL
if ($arResult['PROFILE_PAGE']){
    if (!$USER->IsAuthorized()){
        $arResult['FORM_FIELDS']=[
            $arParams['AUTH_FORM_CODE']=>PersonalUtils::GetFormFileds($auth_form_id, "LOGIN", false, "ВОЙТИ", $active=='auth'?true:false),
            $arParams['REGISTER_FORM_CODE']=>PersonalUtils::GetFormFileds($reg_form_id, "REG_1", false, "ОТПРАВИТЬ", $active=='reg'?true:false),
            $arParams['PASSFORGOT_FORM_CODE']=>PersonalUtils::GetFormFileds($passforgot_form_id, "FORGOT_1", false, "ОТПРАВИТЬ", $active=='forgot'?true:false),
        ];

        $this->IncludeComponentTemplate('auth-page');

    }
    else{
        $arResult['CHANGE']=$_GET['change']=='Y'? true:false;
        $ACTION=$_GET['change']=='Y'? "UPDATE_USER":"EXIT";
        $arResult['LK_FIELDS']=PersonalUtils::GetPersonalPageFormFields($USER->GetID());

        $this->IncludeComponentTemplate('personal');
    }
    $template = & $this->GetTemplate();
    $template->addExternalJs(SITE_TEMPLATE_PATH . '/js/jquery.inputmask.min.js');

    $template->addExternalJs(SITE_TEMPLATE_PATH . '/js/datepicker.min.js');
    $template->addExternalJs(SITE_TEMPLATE_PATH . '/js/datepicker.ru-RU.js');
    $template->addExternalCss(SITE_TEMPLATE_PATH . '/css/datepicker.min.css');
}

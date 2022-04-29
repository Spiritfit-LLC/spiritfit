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
$arResult['FORM_FIELDS']=[
    $arParams['AUTH_FORM_CODE']=>PersonalUtils::GetFormFileds($auth_form_id, "LOGIN"),
    $arParams['REGISTER_FORM_CODE']=>PersonalUtils::GetFormFileds($reg_form_id, "REG", 'registration'),
    $arParams['PASSFORGOT_FORM_CODE']=>PersonalUtils::GetFormFileds($passforgot_form_id, "FORGOT", 'recovery'),
];


global $USER;
global $APPLICATION;


$url =$_SERVER['REQUEST_URI'];
$parts = parse_url($url);
$currentUrl = $parts['path'];

$arResult["AUTH"]=$USER->IsAuthorized();
$arResult["PROFILE_URL"] = $arParams["PROFILE_URL"];
$arResult['ajax']=str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__).'/ajax.php';


if ($currentUrl==$arParams['PROFILE_URL']){
    $arResult['PROFILE_PAGE']=true;
}
else{
    $arResult['PROFILE_PAGE']=false;
}


//НА ВРЕМЯ ТЕСТА ПОКАЗЫВАЕМ ТОЛЬКО НА СТРАНИЦЕ PERSONAL
if ($arResult['PROFILE_PAGE']){
    if (!$USER->IsAuthorized()){
        if ($_GET['reg']=='Y'){
            $this->IncludeComponentTemplate('registration');
        }
        elseif ($_GET['getpass']=='Y'){
            $this->IncludeComponentTemplate('passforgot');
        }
        else{
            $this->IncludeComponentTemplate('authorization');
        }
    }
    else{
        $arResult['CHANGE']=$_GET['change']=='Y'? true:false;
        $ACTION=$_GET['change']=='Y'? "UPDATE_USER":"EXIT";
        $arResult['LK_FIELDS']=PersonalUtils::GetPersonalPageFormFields($USER->GetID(), $arResult['CHANGE'], false, $ACTION);

        $filter = Array
        (
            "LAST_LOGIN_1"        => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time()),
            "ACTIVE"              => "Y",
            "GROUPS_ID"           => Array(7),
            'ID'=>'~'.$USER->GetID()
        );
        $rsUsers = CUser::GetList(($by="last_login"), ($order="desc"), $filter, $arParams); // выбираем пользователей

        $count=0;
        while($arUser = $rsUsers->Fetch()){
            $USER_FIELDS=PersonalUtils::GetPersonalPageFormFields($arUser['ID'], false, false, "", true);
            $arResult['ACTIVE_USERS_LIST'][]=$USER_FIELDS;
            $count++;
            if ($count>50){
                break;
            }
        }
        $this->IncludeComponentTemplate('personal');
        $this->IncludeComponentTemplate('change-photo');
    }
    $template = & $this->GetTemplate();
    $template->addExternalJs(SITE_TEMPLATE_PATH . '/js/jquery.inputmask.min.js');
}

//РАСКОМЕНТИТЬ ПОСЛЕ ТЕСТОВ
//if(!$USER->IsAuthorized()){
//    if (!$arResult['PROFILE_PAGE']){
//        $this->IncludeComponentTemplate('registration');
//        $this->IncludeComponentTemplate('passforgot');
//        $this->IncludeComponentTemplate('authorization');
//    }
//    else{
//        if ($_GET['reg']=='Y'){
//            $this->IncludeComponentTemplate('registration');
//        }
//        elseif ($_GET['getpass']=='Y'){
//            $this->IncludeComponentTemplate('passforgot');
//        }
//        else{
//            $this->IncludeComponentTemplate('authorization');
//        }
//    }
//}
//else {
//    if (!$arResult['PROFILE_PAGE']){
//        $arResult['LK_FIELDS']=PersonalUtils::GetPersonalPageFormFields($USER->GetID(), $arResult['CHANGE'], false, "EXIT");
//        $this->IncludeComponentTemplate('auth');
//    }
//    else{
//        $arResult['CHANGE']=$_GET['change']=='Y'? true:false;
//        $ACTION=$_GET['change']=='Y'? "UPDATE_USER":"EXIT";
//        $arResult['LK_FIELDS']=PersonalUtils::GetPersonalPageFormFields($USER->GetID(), $arResult['CHANGE'], false, $ACTION);
//
//        $filter = Array
//        (
//            "LAST_LOGIN_1"        => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time()),
//            "ACTIVE"              => "Y",
//            "GROUPS_ID"           => Array(7),
//            'ID'=>'~'.$USER->GetID()
//        );
//        $rsUsers = CUser::GetList(($by="last_login"), ($order="desc"), $filter, $arParams); // выбираем пользователей
//
//        $count=0;
//        while($arUser = $rsUsers->Fetch()){
//            $USER_FIELDS=PersonalUtils::GetPersonalPageFormFields($arUser['ID'], false, false, "", true);
//            $arResult['ACTIVE_USERS_LIST'][]=$USER_FIELDS;
//            $count++;
//            if ($count>50){
//                break;
//            }
//        }
//        $this->IncludeComponentTemplate('personal');
//    }
//    $this->IncludeComponentTemplate('change-photo');
//}
//if (!$arResult['PROFILE_PAGE']){
//    $this->IncludeComponentTemplate('onsomepage_btn');
//}
//$template = & $this->GetTemplate();
//$template->addExternalJs(SITE_TEMPLATE_PATH . '/js/jquery.inputmask.min.js');
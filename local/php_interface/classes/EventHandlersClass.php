<?php

AddEventHandler("iblock", "OnBeforeIBlockAdd", Array("EventHandlersClass", "OnAddCheckIBlockSIDHandler"));
AddEventHandler("iblock", "OnBeforeIBlockUpdate", Array("EventHandlersClass", "OnUpdateCheckIBlockSIDHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("EventHandlersClass", "OnAddCheckIBlockElementSIDHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("EventHandlersClass", "OnUpdateCheckIBlockElementSIDHandler"));
AddEventHandler("iblock", "OnBeforeIBlockSectionAdd", Array("EventHandlersClass", "OnAddCheckIBlockSectionSIDHandler"));
AddEventHandler("iblock", "OnBeforeIBlockSectionUpdate", Array("EventHandlersClass", "OnUpdateCheckIBlockSectionSIDHandler"));


class EventHandlersClass{
    function OnAddCheckIBlockSIDHandler(&$arFields){
        if(strlen($arFields["CODE"])<=0)
        {
            global $APPLICATION;
            $APPLICATION->throwException("Введите символьный код.");
            return false;
        }
        $dbRes=CIBlock::GetList(array(), array('CODE'=>$arFields['CODE']));
        if ($dbRes->Fetch()){
            global $APPLICATION;
            $APPLICATION->throwException("Ошибка: символьный код уже существует");
            return false;
        }
    }

    function OnUpdateCheckIBlockSIDHandler(&$arFields){
        if(strlen($arFields["CODE"])<=0)
        {
            global $APPLICATION;
            $APPLICATION->throwException("Введите символьный код.");
            return false;
        }
        $dbRes=CIBlock::GetList(array(), array('CODE'=>$arFields['CODE'], '!ID'=>$arFields['ID']));
        if ($dbRes->Fetch()){
            global $APPLICATION;
            $APPLICATION->throwException("Ошибка: символьный код уже существует");
            return false;
        }
    }

    function OnAddCheckIBlockElementSIDHandler(&$arFields){
        if(strlen($arFields["CODE"])<=0)
        {
            global $APPLICATION;
            $APPLICATION->throwException("Введите символьный код.");
            return false;
        }
        $dbRes=CIBlockElement::GetList(Array(),array('CODE'=>$arFields['CODE']));
        if ($dbRes->Fetch()){
            global $APPLICATION;
            $APPLICATION->throwException("Ошибка: символьный код уже существует");
            return false;
        }
    }
    function OnUpdateCheckIBlockElementSIDHandler(&$arFields){
        if(strlen($arFields["CODE"])<=0)
        {
            global $APPLICATION;
            $APPLICATION->throwException("Введите символьный код.");
            return false;
        }
        $dbRes=CIBlockElement::GetList(Array(),array('CODE'=>$arFields['CODE'], '!ID'=>$arFields['ID']));
        if ($dbRes->Fetch()){
            global $APPLICATION;
            $APPLICATION->throwException("Ошибка: символьный код уже существует");
            return false;
        }
    }

    function OnAddCheckIBlockSectionSIDHandler(&$arFields){
        if(strlen($arFields["CODE"])<=0)
        {
            global $APPLICATION;
            $APPLICATION->throwException("Введите символьный код.");
            return false;
        }
        $dbRes=CIBlockSection::GetList(Array(),array('CODE'=>$arFields['CODE']));
        if ($dbRes->Fetch()){
            global $APPLICATION;
            $APPLICATION->throwException("Ошибка: символьный код уже существует");
            return false;
        }
    }
    function OnUpdateCheckIBlockSectionSIDHandler(&$arFields){
        if(strlen($arFields["CODE"])<=0)
        {
            global $APPLICATION;
            $APPLICATION->throwException("Введите символьный код.");
            return false;
        }
        $dbRes=CIBlockSection::GetList(Array(),array('CODE'=>$arFields['CODE'], '!ID'=>$arFields['ID']));
        if ($dbRes->Fetch()){
            global $APPLICATION;
            $APPLICATION->throwException("Ошибка: символьный код уже существует");
            return false;
        }
    }
}

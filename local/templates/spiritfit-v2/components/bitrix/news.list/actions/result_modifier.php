<?php

$arResult["SETTINGS"] = Utils::getInfo();

foreach ($arResult["ITEMS"] as $key => $arItem) {
    if ($arItem["DATE_ACTIVE_FROM"]) {
        $monthFrom = CIBlockFormatProperties::DateFormat('F', MakeTimeStamp($arItem["DATE_ACTIVE_FROM"], CSite::GetDateFormat()));
        $monthTo = CIBlockFormatProperties::DateFormat('F', MakeTimeStamp($arItem["DATE_ACTIVE_TO"], CSite::GetDateFormat()));
    
        if ($monthFrom == $monthTo) {
            $formatFrom = 'j';
        } else {
            $formatFrom = 'j F';
        }
    
        $dateFrom = CIBlockFormatProperties::DateFormat($formatFrom, MakeTimeStamp($arItem["DATE_ACTIVE_FROM"], CSite::GetDateFormat()));
        $dateTo = CIBlockFormatProperties::DateFormat('j F', MakeTimeStamp($arItem["DATE_ACTIVE_TO"], CSite::GetDateFormat()));
    
        if ($dateFrom && $dateTo) {
            $arResult["ITEMS"][$key]["DATE"] = $dateFrom . ' - ' . $dateTo;
        } elseif ($dateFrom) {
            $arResult["ITEMS"][$key]["DATE"] = $dateFrom;
        }
    }
}

$arResult["URL_ABONEMENT"] = Utils::getUrlAbonement();
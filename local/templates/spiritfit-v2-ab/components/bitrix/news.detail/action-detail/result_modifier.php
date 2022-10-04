<?php


$page_element=$APPLICATION->GetCurPage();

$url_parts = explode("/", $page_element);
$last = count($url_parts)-1;
if ($url_parts[$last]=="") $last--;
$code = $url_parts[$last];
$code = preg_replace('/(.*).(php|html|htm)/', '$1', $code);

$id = CIBlockFindTools::GetElementID(false, $code, false, false, array());

if (empty($id)){
    Bitrix\Iblock\Component\Tools::process404(
        trim($arParams["MESSAGE_404"]) ?: GetMessage("CATALOG_SECTION_NOT_FOUND")
        ,true
        ,$arParams["SET_STATUS_404"] === "Y"
        ,$arParams["SHOW_404"] === "Y"
        ,$arParams["FILE_404"]
    );
}

$arResult["SETTINGS"] = Utils::getInfo();

if ($arResult["DATE_ACTIVE_FROM"]) {
    $monthFrom = CIBlockFormatProperties::DateFormat('F', MakeTimeStamp($arResult["DATE_ACTIVE_FROM"], CSite::GetDateFormat()));
    $monthTo = CIBlockFormatProperties::DateFormat('F', MakeTimeStamp($arResult["DATE_ACTIVE_TO"], CSite::GetDateFormat()));
    
    if ($monthFrom == $monthTo) {
        $formatFrom = 'j';
    } else {
        $formatFrom = 'j F';
    }
    
    $dateFrom = CIBlockFormatProperties::DateFormat($formatFrom, MakeTimeStamp($arResult["DATE_ACTIVE_FROM"], CSite::GetDateFormat()));
    $dateTo = CIBlockFormatProperties::DateFormat('j F', MakeTimeStamp($arResult["DATE_ACTIVE_TO"], CSite::GetDateFormat()));
    
    if ($dateFrom && $dateTo) {
        $arResult["DATE"] = $dateFrom . ' - ' . $dateTo;
    } elseif ($dateFrom) {
        $arResult["DATE"] = $dateFrom;
    }
}

$arResult["URL_ABONEMENT"] = Utils::getUrlAbonement();

$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arResult["IBLOCK_ID"], $arResult["ID"]);
$seoValues = $ipropValues->getValues();

if ($seoValues['ELEMENT_META_TITLE']) {
    $arResult['SEO']['ELEMENT_META_TITLE'] = $seoValues['ELEMENT_META_TITLE'];
    $APPLICATION->SetPageProperty('title',$arResult['SEO']['ELEMENT_META_TITLE']); 
}
if ($seoValues['ELEMENT_META_DESCRIPTION']) {
    $arResult['SEO']['ELEMENT_META_DESCRIPTION'] = $seoValues['ELEMENT_META_DESCRIPTION'];
    $APPLICATION->SetPageProperty('description',$arResult['SEO']['ELEMENT_META_DESCRIPTION']); 
}
if ($seoValues['ELEMENT_META_KEYWORDS']) {
    $arResult['SEO']['ELEMENT_META_KEYWORDS'] = $seoValues['ELEMENT_META_KEYWORDS'];
}
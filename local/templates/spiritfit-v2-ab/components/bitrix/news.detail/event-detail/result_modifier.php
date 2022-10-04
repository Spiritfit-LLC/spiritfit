<?php

\Bitrix\Main\Loader::includeModule('iblock');

$page_element=$APPLICATION->GetCurPage();

$url_parts = explode("/", $page_element);
if (count($url_parts)>4){
    Bitrix\Iblock\Component\Tools::process404(
        trim($arParams["MESSAGE_404"]) ?: GetMessage("CATALOG_SECTION_NOT_FOUND")
        ,true
        ,$arParams["SET_STATUS_404"] === "Y"
        ,$arParams["SHOW_404"] === "Y"
        ,$arParams["FILE_404"]
    );
}

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

foreach ($arResult["PROPERTIES"]["PHOTO_GALLERY"]["VALUE"] as $photo) {
    /*$arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"][] = CFile::GetPath($photo);*/
    $arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"][] = [
        "SRC" => CFile::GetPath($photo),
        "SRC_1280" => CFile::ResizeImageGet($photo, array('width' => 1280, 'height' => 800), BX_RESIZE_IMAGE_PROPORTIONAL)["src"],
        "SRC_800" => CFile::ResizeImageGet($photo, array('width' => 800, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL)["src"],
        "SRC_450" => CFile::ResizeImageGet($photo, array('width' => 450, 'height' => 281), BX_RESIZE_IMAGE_PROPORTIONAL)["src"]
    ];
}

$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arResult["IBLOCK_ID"], $arResult["ID"]);
$seoValues = $ipropValues->getValues();

if ($seoValues['ELEMENT_META_TITLE']) {
    $arResult['SEO']['ELEMENT_META_TITLE'] = $seoValues['ELEMENT_META_TITLE'];
    $APPLICATION->SetPageProperty('title', $arResult["SEO"]["ELEMENT_META_TITLE"]);
}else{
    $arResult['SEO']['ELEMENT_META_TITLE'] = strip_tags($arResult["~NAME"]);
}
if ($seoValues['ELEMENT_META_DESCRIPTION']) {
    $arResult['SEO']['ELEMENT_META_DESCRIPTION'] = $seoValues['ELEMENT_META_DESCRIPTION'];
    $APPLICATION->SetPageProperty('description',$arResult["SEO"]["ELEMENT_META_DESCRIPTION"]);
}
if ($seoValues['ELEMENT_META_KEYWORDS']) {
    $arResult['SEO']['ELEMENT_META_KEYWORDS'] = $seoValues['ELEMENT_META_KEYWORDS'];
}

$denyInAdddress = ['этаж', 'г.', 'город', 'м.', 'метро', 'эт.', 'ТРЦ', 'трц', 'к.', 'корп', 'корпус', '"'];
if( !empty($arResult['PROPERTIES']['ADRESS']['VALUE']['TEXT']) ) {
    $addressArr = explode(',', $arResult['PROPERTIES']['ADRESS']['VALUE']['TEXT']);
    $clearAddress = [];
    foreach($addressArr as $aStr) {
        $needAdd = true;
        foreach($denyInAdddress as $dStr) {
            if( strpos($aStr, $dStr) !== false ) {
                $needAdd = false;
                break;
            }
        }
        if( $needAdd ) {
            $clearAddress[] = $aStr;
        }
    }
    $arResult['ADDRESS_SHORT'] = implode(',', $clearAddress);
}
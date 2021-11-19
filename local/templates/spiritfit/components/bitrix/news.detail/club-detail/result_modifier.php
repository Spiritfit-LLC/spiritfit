<?php

\Bitrix\Main\Loader::includeModule('iblock');


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

foreach ($arResult["PROPERTIES"]["PHOTO_GALLERY"]["VALUE"] as $photo) {
    $arResult["PROPERTIES"]["PHOTO_GALLERY"]["ITEMS"][] = CFile::GetPath($photo);
}

$arResult["PROPERTIES"]["SCHEDULE"]["SRC"] = CFile::GetPath($arResult["PROPERTIES"]["SCHEDULE"]["VALUE"]);

if ($arResult["PROPERTIES"]["PREVIEW_VIDEO"]["VALUE"]) {
    $arResult["PROPERTIES"]["PREVIEW_VIDEO"]["SRC"] = CFile::GetPath($arResult["PROPERTIES"]["PREVIEW_VIDEO"]["VALUE"]);
} else {
    preg_match("/(watch\?v=(.*))|((?!.*\/).*?$)/", $arResult["PROPERTIES"]["LINK_VIDEO"]["VALUE"], $match);
    $match = $match[2] ? $match[2] : $match[3];

    if ($match) {
        $arResult["PROPERTIES"]["PREVIEW_VIDEO"]["SRC"] = "http://i3.ytimg.com/vi/" . $match . "/maxresdefault.jpg";
    }
}

if ($arResult["PROPERTIES"]["LINK_VIDEO"]["VALUE"]) {
    preg_match("/(watch\?v=(.*))|((?!.*\/).*?$)/", $arResult["PROPERTIES"]["LINK_VIDEO"]["VALUE"], $match);
    $match = $match[2] ? $match[2] : $match[3];

    if ($match) {
        $arResult["PROPERTIES"]["LINK_VIDEO"]["VALUE"] = $match;
    }
}

$arFilter = array(
    "IBLOCK" => $arResult["PROPERTIES"]["ADVANTAGES"]["LINK_IBLOCK_ID"],
    "ID" => $arResult["PROPERTIES"]["ADVANTAGES"]["VALUE"],
    "ACTIVE" => "Y",
);
$dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "NAME", "PREVIEW_PICTURE", "DETAIL_TEXT", "PREVIEW_TEXT"));

while ($res = $dbElements->GetNext()) {
    $res["PICTURE"] = CFile::ResizeImageGet($res["PREVIEW_PICTURE"], array("width" => "420", "height" => "480", BX_RESIZE_IMAGE_PROPORTIONAL));
    $arResult["PROPERTIES"]["ADVANTAGES"]["ITEMS"][] = $res;
}

$itemGetListArray['filter'] = ["IBLOCK_ID" => $arResult["PROPERTIES"]["TEAM"]["LINK_IBLOCK_ID"], "ID" => $arResult["PROPERTIES"]["TEAM"]["VALUE"], "ACTIVE" => "Y"];
$itemGetListArray['select'] = ["ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "PREVIEW_TEXT"];
$itemRes = \Bitrix\Iblock\ElementTable::getList($itemGetListArray);
while ($item = $itemRes->Fetch()) {
    $item["PICTURE"] = CFile::ResizeImageGet($item["PREVIEW_PICTURE"], array("width" => "500", "height" => "600", BX_RESIZE_IMAGE_PROPORTIONAL));
    $itemPropertyRes = \CIBlockElement::getProperty(
        $item['IBLOCK_ID'],
        $item['ID'],
        [],
        ["CODE" => ["BACK_IMAGE", "BACK_TEXT", "BACK_TEXT_COLOR", "POSITION"]]
    );
    while ($itemProperty = $itemPropertyRes->Fetch()) {
        $item['PROPERTIES'][$itemProperty['CODE']] = $itemProperty;
    }
    if ($item['PROPERTIES']['BACK_IMAGE']['VALUE']) {
        $item['BACK']['IMAGE'] = CFile::ResizeImageGet($item['PROPERTIES']['BACK_IMAGE']['VALUE'], array("width" => "500", "height" => "600", BX_RESIZE_IMAGE_PROPORTIONAL))['src'];
    }
    if ($item['PROPERTIES']['BACK_TEXT']['VALUE']) {
        $item['BACK']['TEXT'] = $item['PROPERTIES']['BACK_TEXT']['VALUE']['TEXT'];
    }
    if ($item['PROPERTIES']['BACK_TEXT_COLOR']['VALUE']) {
        $colorGetListArray['filter'] = ["=ID" => $item['PROPERTIES']['BACK_TEXT_COLOR']['VALUE'], "IBLOCK_ID" => IBLOCK_COLORS_ID];
        $colorGetListArray['select'] = ["CODE"];
        $colorRes = \Bitrix\Iblock\ElementTable::getList($colorGetListArray);
        if ($color = $colorRes->Fetch()) {
            $item['BACK']['COLOR'] = $color['CODE'];
        }
    }
    $arResult["PROPERTIES"]["TEAM"]["ITEMS"][] = $item;
}

$arResult["PROPERTIES"]["TEAM"]["ITEMS"] = array_chunk($arResult["PROPERTIES"]["TEAM"]["ITEMS"], 2);

$arResult["URL_ABONEMENT"] = Utils::getUrlAbonement() . "?club=" . $arResult["PROPERTIES"]["NUMBER"]["VALUE"];

$arFilter = array("IBLOCK_CODE" => "subscription", "PROPERTY_PRICE" => false, "ACTIVE" => "Y");
$dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, array("ID", "NAME", "DETAIL_PAGE_URL"));

if ($res = $dbElements->GetNext()) {
    $arResult["URL_TRIAL"] = $res["DETAIL_PAGE_URL"];
}

if ($arResult["PROPERTIES"]["PHONE"]["~VALUE"]) {
    $arResult["PROPERTIES"]["PHONE"]["CALL"] = preg_replace('![^0-9]+!', '', $arResult["PROPERTIES"]["PHONE"]["~VALUE"]);
}

$arResult["INFO"] = Utils::getInfo();

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

/**
 * Абонементы
 */


$arFilter = array("IBLOCK_CODE" => "subscription", "ACTIVE" => "Y", "ID" => $arResult['PROPERTIES']['ABONEMENTS']['VALUE']);
$dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false);

while ($res = $dbElements->GetNextElement()) {
    $fields = $res->GetFields();
    $properties = $res->GetProperties();
    $fields['PROPERTIES'] = $properties;
    $arResult["ABONEMENTS"][] = $fields;
}

$selectedClub["ID"] = $arResult['ID'];

foreach ($arResult["ABONEMENTS"] as $key => $arItem) {
    $club = false;  
    
    /*!*/
    $props = array();
    $arFilter = array("IBLOCK_CODE" => "price_sign");
    $dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("NAME", "PROPERTY_MONTH"));

    while ($res = $dbElements->fetch()) {
        $props[$res["NAME"]] = $res["PROPERTY_MONTH_VALUE"];
    }

    foreach ($arResult["ABONEMENTS"][$key]["PROPERTIES"]["PRICE"]["VALUE"] as $arPrice) {
        if ($arPrice["LIST"] == $selectedClub["ID"]) {
            $club = true;
            $arResult["ABONEMENTS"][$key]["PRICES"][] = $arPrice;
            if($arResult["ABONEMENTS"][$key]['ID'] == ABONEMENTS_GOD_FITNESA_ID) {
                break;
            }  
        }
    }
    foreach ($arResult["ABONEMENTS"][$key]["PROPERTIES"]["BASE_PRICE"]["VALUE"] as $arPrice) {
        if ($arPrice["LIST"] == $selectedClub["ID"]) {
            $arResult["ABONEMENTS"][$key]["BASE_PRICE"] = $arPrice;
            break;
        }
    }

    $priceSign = array();
    foreach ($arResult["ABONEMENTS"][$key]["PROPERTIES"]["PRICE_SIGN_DETAIL"]["VALUE"] as $arItem) {
        if ($arItem["LIST"] == $selectedClub["ID"]) {
            $priceSign[] = $arItem;
        }
    }
    
    foreach ($arResult["ABONEMENTS"][$key]["PRICES"] as $keyP => $arPrice) {
        if ($arPrice["PRICE"] != $arResult["ABONEMENTS"][$key]["BASE_PRICE"]["PRICE"] && $arPrice["NUMBER"] == $arResult["ABONEMENTS"][$key]["BASE_PRICE"]["NUMBER"]) {
            $arResult["ABONEMENTS"][$key]["SALE"] = $arPrice["PRICE"];

            $arResult["ABONEMENTS"][$key]["PRICES"][$keyP]["PRICE"] = $arResult["ABONEMENTS"][$key]["BASE_PRICE"]["PRICE"];
        }
        
        if ($priceSign) {
            $sign = array_search($arPrice["NUMBER"], array_column($priceSign, "NUMBER"));
        } else {
            $sign = false;
        }
        
        if ($sign !== false) {
            $arResult["ABONEMENTS"][$key]["PRICES"][$keyP]["SIGN"] = $priceSign[$sign]["PRICE"];
        }

        if ($props[$arPrice["NUMBER"]] && $sign === false) {
            $arResult["ABONEMENTS"][$key]["PRICES"][$keyP]["SIGN"] = $props[$arPrice["NUMBER"]];
        }
    };

    array_multisort(array_column($arResult["ABONEMENTS"][$key]["PRICES"], "NUMBER"), SORT_ASC, $arResult["ABONEMENTS"][$key]["PRICES"]);

    if (!$club&&!$arResult["PROPERTIES"]["SOON"]["VALUE"]) {
        unset($arResult["ABONEMENTS"][$key]);
    }
}
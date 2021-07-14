<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);


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



$arResult["PROPERTIES"]["ICON_DETAIL"]["RESIZE"] = CFile::ResizeImageGet($arResult["PROPERTIES"]["ICON_DETAIL"]["VALUE"], array("width"=>"200", "height"=>"120", BX_RESIZE_IMAGE_PROPORTIONAL));

$arResult["PROPERTIES"]["ICON_MAIN"]["RESIZE"] = CFile::ResizeImageGet($arResult["PROPERTIES"]["ICON_MAIN"]["VALUE"], array("width"=>"200", "height"=>"120", BX_RESIZE_IMAGE_PROPORTIONAL));

$arFilter = array(
    "IBLOCK" => $arResult["PROPERTIES"]["ENTER_SYSTEM"]["LINK_IBLOCK_ID"], 
    "ID" => $arResult["PROPERTIES"]["ENTER_SYSTEM"]["VALUE"],
    "ACTIVE" => "Y"
);
$dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "NAME", "PREVIEW_PICTURE"));

while ($res = $dbElements->GetNext()) {
    $res["PICTURE"] = CFile::ResizeImageGet($res["PREVIEW_PICTURE"], array("width"=>"65", "height"=>"65", BX_RESIZE_IMAGE_PROPORTIONAL));
    $arResult["PROPERTIES"]["ENTER_SYSTEM"]["ITEMS"][] = $res;
}

$arResult["PROPERTIES"]["ENTER_SYSTEM"]["ITEMS"] = array_chunk($arResult["PROPERTIES"]["ENTER_SYSTEM"]["ITEMS"], 3);

$countHowUse = count($arResult["PROPERTIES"]["HOW_USE"]["VALUE"]);
$arResult["PROPERTIES"]["HOW_USE"]["ITEMS"] = array_chunk($arResult["PROPERTIES"]["HOW_USE"]["VALUE"], ceil($countHowUse / 2), true);

$arFilter = array(
    "IBLOCK" => $arResult["PROPERTIES"]["REVIEWS"]["LINK_IBLOCK_ID"], 
    "ID" => $arResult["PROPERTIES"]["REVIEWS"]["VALUE"],
    "ACTIVE" => "Y"
);
$dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT"));

while ($res = $dbElements->GetNext()) {
    $res["PICTURE"] = CFile::ResizeImageGet($res["PREVIEW_PICTURE"], array("width"=>"110", "height"=>"110", BX_RESIZE_IMAGE_PROPORTIONAL));
    $arResult["PROPERTIES"]["REVIEWS"]["ITEMS"][] = $res;
}

$arFilter = array(
    "IBLOCK" => $arResult["PROPERTIES"]["OPPORTUNITIES"]["LINK_IBLOCK_ID"], 
    "ID" => $arResult["PROPERTIES"]["OPPORTUNITIES"]["VALUE"],
    "ACTIVE" => "Y"
);
$dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT"));

while ($res = $dbElements->GetNext()) {
    $res["PICTURE"] = CFile::ResizeImageGet($res["PREVIEW_PICTURE"], array("width"=>"310", "height"=>"490", BX_RESIZE_IMAGE_PROPORTIONAL));
    $arResult["PROPERTIES"]["OPPORTUNITIES"]["ITEMS"][] = $res;
}

$arResult["SETTINGS"] = Utils::getInfo();

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

$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arResult["IBLOCK_ID"], $arResult["ID"]);
$seoValues = $ipropValues->getValues();

if ($seoValues['ELEMENT_META_TITLE']) {
    $arResult['SEO']['ELEMENT_META_TITLE'] = $seoValues['ELEMENT_META_TITLE'];
    $APPLICATION->SetPageProperty('title', $arResult["SEO"]["ELEMENT_META_TITLE"]); 
} else {
	$arResult['SEO']['ELEMENT_META_TITLE'] = strip_tags($arResult["~NAME"]);
}
if ($seoValues['ELEMENT_META_DESCRIPTION']) {
    $arResult['SEO']['ELEMENT_META_DESCRIPTION'] = $seoValues['ELEMENT_META_DESCRIPTION'];
    $APPLICATION->SetPageProperty('description', $arResult["SEO"]["ELEMENT_META_DESCRIPTION"]);
}
if ($seoValues['ELEMENT_META_KEYWORDS']) {
    $arResult['SEO']['ELEMENT_META_KEYWORDS'] = $seoValues['ELEMENT_META_KEYWORDS'];
}

foreach ($arResult['PROPERTIES']['GALLERY']['VALUE'] as $key => $value) {
    $arResult['PROPERTIES']['GALLERY']['VALUE_SRC'][$key] = CFile::GetPath($value);
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

$selectedClub = Utils::getClubById($list);
foreach ($arResult["ABONEMENTS"] as $key => $arItem) {
    
    /*!*/
    $props = array();
    $arFilter = array("IBLOCK_CODE" => "price_sign");
    $dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("NAME", "PROPERTY_MONTH"));

    while ($res = $dbElements->fetch()) {
        $props[$res["NAME"]] = $res["PROPERTY_MONTH_VALUE"];
    }

    foreach ($arResult["ABONEMENTS"][$key]["PROPERTIES"]["PRICE"]["VALUE"] as $arPrice) {
        if ($arPrice["LIST"] == $selectedClub["ID"]) {
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
}
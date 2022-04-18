<?
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

$props = array();
$arFilter = array("IBLOCK_CODE" => "price_sign");
$dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("NAME", "PROPERTY_MONTH"));

while ($res = $dbElements->fetch()) {
    $props[$res["NAME"]] = $res["PROPERTY_MONTH_VALUE"];
}

$selectedClub = Utils::getClub($_REQUEST["club"]);

foreach ($arResult["PROPERTIES"]["PRICE"]["VALUE"] as $key => $arPrice) {
    if ($arPrice["LIST"] == $selectedClub["ID"]) {
        $arResult["PRICES"][] = $arPrice;
    }
}

foreach ($arResult["PROPERTIES"]["BASE_PRICE"]["VALUE"] as $key => $arPrice) {
    if ($arPrice["LIST"] == $selectedClub["ID"]) {
        $arResult["BASE_PRICE"] = $arPrice;
        break;
    }
}

$priceSign = array();
foreach ($arResult["PROPERTIES"]["PRICE_SIGN_DETAIL"]["VALUE"] as $arItem) {
    if ($arItem["LIST"] == $selectedClub["ID"]) {
        $priceSign[] = $arItem;
    }
}

foreach ($arResult["PRICES"] as $key => $arPrice) {
    if ($arPrice["PRICE"] != $arResult["BASE_PRICE"]["PRICE"] && $arPrice["NUMBER"] == $arResult["BASE_PRICE"]["NUMBER"]) {
        $arResult["SALE"] = $arPrice["PRICE"];
        $arResult["PRICES"][$key]["PRICE"] = $arResult["BASE_PRICE"]["PRICE"];
    }
    
    if ($priceSign) {
        $sign = array_search($arPrice["NUMBER"], array_column($priceSign, "NUMBER"));
    } else {
        $sign = false;
    }
    
    if ($sign !== false) {
        $arResult["PRICES"][$key]["SIGN"] = $priceSign[$sign]["PRICE"];
    }

    if ($props[$arPrice["NUMBER"]] && $sign === false) {
        $arResult["PRICES"][$key]["SIGN"] = $props[$arPrice["NUMBER"]];
    }
}

array_multisort(array_column($arResult["PRICES"], "NUMBER"), SORT_ASC, $arResult["PRICES"]);
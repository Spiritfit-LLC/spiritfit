<?php
	
	use \ImageConverter\Picture;

$dbSections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "NAME", "DESCRIPTION", "UF_LINK_FAQ"));
if ($res = $dbSections->GetNext()) {
    $arResult["SECTION"] = $res;
}

$arClubs = [];

foreach ($arResult["ITEMS"] as $key => $arItem) {
    $arResult["ITEMS"][$key]["MIN_PRICE"] = min(array_column($arItem["PROPERTIES"]["PRICE"]["VALUE"], "PRICE"));

    foreach ($arItem["PROPERTIES"]["PRICE"]["VALUE"] as $arPrice) {
        if ($arPrice["NUMBER"] > 2) {
            if ($arPrice["PRICE"] == $arResult["ITEMS"][$key]["MIN_PRICE"]) {
                $arResult["ITEMS"][$key]["MIN_PRICE"] = ceil($arResult["ITEMS"][$key]["MIN_PRICE"] / $arPrice["NUMBER"]);
                break;
            }
        }
    }

    $index = $key % 2 == 0 ? $key + 1 : $key - 1;

    if ($arItem["PROPERTIES"]["SIZE"]["VALUE_XML_ID"] == "SMALL") {
        if ($arResult["ITEMS"][$index]["PROPERTIES"]["SIZE"]["VALUE_XML_ID"] == "SMALL") {
            $arResult["ITEMS"][$key]["SIZE"] = "SMALL";
        } else {
            $arResult["ITEMS"][$key]["SIZE"] = "BIG";
        }
    } else {
        $arResult["ITEMS"][$key]["SIZE"] = "BIG";
    }
    
    foreach ($arItem['PROPERTIES']['PRICE']['VALUE'] as $price) {
        $arClubs[$price['LIST']] = $price['LIST'];
    }
}

/**
 * Клубы
 */
$club = (!empty($_REQUEST['club']) ? $_REQUEST['club'] : '00');
$arResult["CLUBS"] = [];
$arFilter = array(
    "IBLOCK_CODE" => "clubs",
    "ACTIVE" => "Y",
    "ID" => array_values($arClubs)
);
$abonementClub = false;
$dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, array("ID", "NAME", "PROPERTY_NUMBER"));
while ($res = $dbElements->GetNext()) {
    $arResult["CLUBS"][] = [
        'NUMBER' => $res['PROPERTY_NUMBER_VALUE'],
        'NAME' => $res['~NAME'],
        'ID' => $res['ID']
    ];
    if ($club == $res['PROPERTY_NUMBER_VALUE']) {
        $abonementClub = true;
    }
};


/**
 * Цены абонементов
 */

$selectedClub = 0;
if ($abonementClub){ 
    $selectedClub = Utils::getClub($club);    
}

if ($selectedClub) {
    foreach ($arResult["ITEMS"] as $key => $arItem) {
        $props = array();
        $arFilter = array("IBLOCK_CODE" => "price_sign");
        $dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("NAME", "PROPERTY_MONTH"));
    
        while ($res = $dbElements->fetch()) {
            $props[$res["NAME"]] = $res["PROPERTY_MONTH_VALUE"];
        }

        foreach ($arResult["ITEMS"][$key]["PROPERTIES"]["PRICE"]["VALUE"] as $arPrice) {
            if ($arPrice["LIST"] == $selectedClub['ID']) {
                $arResult["ITEMS"][$key]["PRICES"][] = $arPrice;
                if($arResult["ITEMS"][$key]['ID'] == ABONEMENTS_GOD_FITNESA_ID) {
                    break;
                }  
            }
        }

        foreach ($arResult["ITEMS"][$key]["PROPERTIES"]["BASE_PRICE"]["VALUE"] as $arPrice) {
            if ($arPrice["LIST"] == $selectedClub['ID']) {
                $arResult["ITEMS"][$key]["BASE_PRICE"] = $arPrice;
                break;
            }
        }

        $priceSign = array();
        foreach ($arResult["ITEMS"][$key]["PROPERTIES"]["PRICE_SIGN_DETAIL"]["VALUE"] as $arItem) {
            if ($arItem["LIST"] == $selectedClub['ID']) {
                $priceSign[] = $arItem;
            }
        }
        foreach ($arResult["ITEMS"][$key]["PRICES"] as $keyP => $arPrice) {
            if ($arPrice["PRICE"] != $arResult["ITEMS"][$key]["BASE_PRICE"]["PRICE"] && $arPrice["NUMBER"] == $arResult["ITEMS"][$key]["BASE_PRICE"]["NUMBER"]) {
                $arResult["ITEMS"][$key]["SALE"] = $arPrice["PRICE"];

                $arResult["ITEMS"][$key]["PRICES"][$keyP]["PRICE"] = $arResult["ITEMS"][$key]["BASE_PRICE"]["PRICE"];
            }
            
            if ($priceSign) {
                $sign = array_search($arPrice["NUMBER"], array_column($priceSign, "NUMBER"));
            } else {
                $sign = false;
            }
            
            if ($sign !== false) {
                $arResult["ITEMS"][$key]["PRICES"][$keyP]["SIGN"] = $priceSign[$sign]["PRICE"];
            }

            if ($props[$arPrice["NUMBER"]] && $sign === false) {
                $arResult["ITEMS"][$key]["PRICES"][$keyP]["SIGN"] = $props[$arPrice["NUMBER"]];
            }
        };

        array_multisort(array_column($arResult["ITEMS"][$key]["PRICES"], "NUMBER"), SORT_ASC, $arResult["ITEMS"][$key]["PRICES"]);

        if (!$arResult["ITEMS"][$key]["PRICES"]) {
            //unset($arResult["ITEMS"][$key]);
        }
    }
}


$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams["IBLOCK_ID"], $arResult["SECTION"]["ID"]);
$seoValues = $ipropValues->getValues();

if ($seoValues['SECTION_META_TITLE']) {
   $arResult['SEO']['SECTION_META_TITLE'] = $seoValues['SECTION_META_TITLE'];
}
if ($seoValues['SECTION_META_DESCRIPTION']) {
    $arResult['SEO']['SECTION_META_DESCRIPTION'] = $seoValues['SECTION_META_DESCRIPTION'];
}
if ($seoValues['SECTION_META_KEYWORDS']) {
    $arResult['SEO']['SECTION_META_KEYWORDS'] = $seoValues['SECTION_META_KEYWORDS'];
}

/*WebP*/
foreach( $arResult["ITEMS"] as &$arItem) {
	$arItem['PREVIEW_PICTURE_WEBP'] = [];
	if( !empty($arItem['PREVIEW_PICTURE']) ) {
		$arItem['PREVIEW_PICTURE_WEBP'] = Picture::getResizeWebp($arItem['PREVIEW_PICTURE'], 379, 580, false);
	}
}
unset($arItem);
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

//////////////////////////////////////////////////////////////////////////
//Временное решение, пока не отошли от дублей. НЕ ЗАБУДЬ УДАЛИТЬ ЭТО ПОТОМ
if ($code=="gorod-odintsovo"){
    $url_parts[$last]="odintsovo";
    $REDIRECT_URL=implode('/', $url_parts);
    LocalRedirect($REDIRECT_URL.'?form=t-drive', false, 301);
}
//////////////////////////////////////////////////////////////////////////

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

$order=array('SORT' => 'ASC', 'NAME' => 'ASC');
$filter=array("IBLOCK_ID" => $arResult["PROPERTIES"]["TEAM"]["LINK_IBLOCK_ID"], "ID" => $arResult["PROPERTIES"]["TEAM"]["VALUE"], "ACTIVE" => "Y");
$select=array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "PREVIEW_TEXT", "HOVER_GIF");
$itemRes=CIBlockElement::GetList($order, $filter, false, false, $select);
while($item=$itemRes->Fetch()){
    $item["PICTURE"] = CFile::ResizeImageGet($item["PREVIEW_PICTURE"], array("width" => "379", "height" => "580", BX_RESIZE_IMAGE_PROPORTIONAL))["src"];
    $itemPropertyRes = CIBlockElement::GetProperty(
        $item['IBLOCK_ID'],
        $item['ID'],
        array("sort" => "asc")
    );
    $arResult["TEST"]=$itemPropertyRes->Fetch();
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
//    if (!empty($item["PROPERTIES"]["HOVER_GIF"]["VALUE"])){
//        $item["HOVER_GIF"]["SRC"]=CFile::GetPath($item["PROPERTIES"]["HOVER_GIF"]["VALUE"]);
//    }
    $arResult["PROPERTIES"]["TEAM"]["ITEMS"][] = $item;
}



//$itemGetListArray['order'] = ['SORT' => 'ASC', 'NAME' => 'ASC'];
//$itemGetListArray['filter'] = ["IBLOCK_ID" => $arResult["PROPERTIES"]["TEAM"]["LINK_IBLOCK_ID"], "ID" => $arResult["PROPERTIES"]["TEAM"]["VALUE"], "ACTIVE" => "Y"];
//$itemGetListArray['select'] = ["ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "PREVIEW_TEXT"];
//$itemRes = \Bitrix\Iblock\ElementTable::getList($itemGetListArray);
//while ($item = $itemRes->Fetch()) {
//    $item["PICTURE"] = CFile::ResizeImageGet($item["PREVIEW_PICTURE"], array("width" => "379", "height" => "580", BX_RESIZE_IMAGE_PROPORTIONAL))["src"];
//    $itemPropertyRes = \CIBlockElement::getProperty(
//        $item['IBLOCK_ID'],
//        $item['ID'],
//        [],
//        ["CODE" => ["BACK_IMAGE", "BACK_TEXT", "BACK_TEXT_COLOR", "POSITION"]]
//    );
//    while ($itemProperty = $itemPropertyRes->Fetch()) {
//        $item['PROPERTIES'][$itemProperty['CODE']] = $itemProperty;
//    }
//    if ($item['PROPERTIES']['BACK_IMAGE']['VALUE']) {
//        $item['BACK']['IMAGE'] = CFile::ResizeImageGet($item['PROPERTIES']['BACK_IMAGE']['VALUE'], array("width" => "500", "height" => "600", BX_RESIZE_IMAGE_PROPORTIONAL))['src'];
//    }
//    if ($item['PROPERTIES']['BACK_TEXT']['VALUE']) {
//        $item['BACK']['TEXT'] = $item['PROPERTIES']['BACK_TEXT']['VALUE']['TEXT'];
//    }
//    if ($item['PROPERTIES']['BACK_TEXT_COLOR']['VALUE']) {
//        $colorGetListArray['filter'] = ["=ID" => $item['PROPERTIES']['BACK_TEXT_COLOR']['VALUE'], "IBLOCK_ID" => IBLOCK_COLORS_ID];
//        $colorGetListArray['select'] = ["CODE"];
//        $colorRes = \Bitrix\Iblock\ElementTable::getList($colorGetListArray);
//        if ($color = $colorRes->Fetch()) {
//            $item['BACK']['COLOR'] = $color['CODE'];
//        }
//    }
//    $arResult["PROPERTIES"]["TEAM"]["ITEMS"][] = $item;
//}

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


if (!empty($arResult["PROPERTIES"]["NOT_OPEN_YET"]["VALUE"])){
    $abonements=array('ID' => $arResult['PROPERTIES']['ABONEMENTS']['VALUE']);
}
else{
    $abonements=array(
        'LOGIC' => 'OR',
        array('ID' => $arResult['PROPERTIES']['ABONEMENTS']['VALUE']),
        array('!PROPERTY_HIDDEN' => 40),
    );
}

$arFilter = array("IBLOCK_CODE" => "subscription", "ACTIVE" => "Y", $abonements);
$dbElements = CIBlockElement::GetList(array("SORT"=>"ASC"), $arFilter, false, false);

while ($res = $dbElements->GetNextElement()) {
    $fields = $res->GetFields();
    $properties = $res->GetProperties();
    $fields['PROPERTIES'] = $properties;
	
	/*if( !empty($arResult['PROPERTIES']['HIDE_LINK']['VALUE']) && !empty($arResult["PROPERTIES"]["NUMBER"]["VALUE"]) ) {
		$fields['DETAIL_PAGE_URL'] .= $arResult["PROPERTIES"]["NUMBER"]["VALUE"] . '/';
	}*/
//	if( !empty($arResult["PROPERTIES"]["NUMBER"]["VALUE"]) ) {
//		$fields['DETAIL_PAGE_URL'] .= $arResult["PROPERTIES"]["NUMBER"]["VALUE"] . '/';
//	}
	
    $arResult["ABONEMENTS"][] = $fields;
}


$selectedClub["ID"] = $arResult['ID'];

$arResult["ABONEMENTS_MIN_PRICE"] = 0;
$arResult["ABONEMENTS_MAX_PRICE"] = 0;
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
	
		if( $arResult["ABONEMENTS_MIN_PRICE"] == 0 || floatval($arPrice['PRICE']) < $arResult["ABONEMENTS_MIN_PRICE"] ) {
			$arResult["ABONEMENTS_MIN_PRICE"] = floatval($arPrice['PRICE']);
		}
		if( $arResult["ABONEMENTS_MAX_PRICE"] == 0 || floatval($arPrice['PRICE']) > $arResult["ABONEMENTS_MAX_PRICE"] ) {
			$arResult["ABONEMENTS_MAX_PRICE"] = floatval($arPrice['PRICE']);
		}
		
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
	
	if( $arResult["ABONEMENTS_MIN_PRICE"] == 0 || ($arResult["ABONEMENTS"][$key]['SALE_TWO_MONTH'] != 0 && floatval($arResult["ABONEMENTS"][$key]['SALE_TWO_MONTH']) < $arResult["ABONEMENTS_MIN_PRICE"]) ) {
		$arResult["ABONEMENTS_MIN_PRICE"] = floatval($arResult["ABONEMENTS"][$key]['SALE_TWO_MONTH']);
	}
	if( $arResult["ABONEMENTS_MAX_PRICE"] == 0 || ($arResult["ABONEMENTS"][$key]['SALE_TWO_MONTH'] != 0 && floatval($arResult["ABONEMENTS"][$key]['SALE_TWO_MONTH']) > $arResult["ABONEMENTS_MAX_PRICE"]) ) {
		$arResult["ABONEMENTS_MAX_PRICE"] = floatval($arResult["ABONEMENTS"][$key]['SALE_TWO_MONTH']);
	}
	if( $arResult["ABONEMENTS_MIN_PRICE"] == 0 || ($arResult["ABONEMENTS"][$key]['SALE'] != 0 &&  floatval($arResult["ABONEMENTS"][$key]['SALE']) < $arResult["ABONEMENTS_MIN_PRICE"]) ) {
		$arResult["ABONEMENTS_MIN_PRICE"] = floatval($arResult["ABONEMENTS"][$key]['SALE']);
	}
	if( $arResult["ABONEMENTS_MAX_PRICE"] == 0 || ($arResult["ABONEMENTS"][$key]['SALE'] != 0 &&  floatval($arResult["ABONEMENTS"][$key]['SALE']) > $arResult["ABONEMENTS_MAX_PRICE"]) ) {
		$arResult["ABONEMENTS_MAX_PRICE"] = floatval($arResult["ABONEMENTS"][$key]['SALE']);
	}

    if ($club || $arResult["PROPERTIES"]["SOON"]["VALUE"]){
        $arResult["ABONEMENTS"][$key]['DETAIL_PAGE_URL'] .= $arResult["PROPERTIES"]["NUMBER"]["VALUE"];
    }
//    if (!$club&&!$arResult["PROPERTIES"]["SOON"]["VALUE"]) {
////        unset($arResult["ABONEMENTS"][$key]);
//    }
//    else{
//        $arResult["ABONEMENTS"][$key]['DETAIL_PAGE_URL'] .= $arResult["PROPERTIES"]["NUMBER"]["VALUE"];
//    }
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

if( !empty($arResult["PROPERTIES"]["REVIEWS"]["VALUE"]) ) {
	$res = CIBlockElement::GetList(["SORT" => "ASC"], ["IBLOCK_ID" => $arResult["PROPERTIES"]["REVIEWS"]["LINK_IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $arResult["PROPERTIES"]["REVIEWS"]["VALUE"]], false);
	$arResult["PROPERTIES"]["REVIEWS"]["VALUE"] = [];
	while($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();
		$arFields["PROPERTIES"] = $ob->GetProperties();
		
		if( !empty($arFields["PROPERTIES"]["NAME"]["VALUE"]) ) {
			$arFields["NAME_SHORT"] = mb_substr($arFields["PROPERTIES"]["NAME"]["VALUE"], 0, 1);
		} else {
			$arFields["NAME_SHORT"] = "S";
		}
		
		$arFields["RATING"] = intval($arFields["PROPERTIES"]["RATING"]["VALUE"]);
		$arResult["PROPERTIES"]["REVIEWS"]["VALUE"][] = $arFields;
	}
}


if (!empty($_REQUEST['form'])){
    $res = CIBlockElement::GetByID(Utils::GetIBlockElementIDBySID($_REQUEST['form']));
    if($ar_res = $res->GetNextElement()){
        $props=$ar_res->GetProperties();
        $FORM_TYPE=$props['FORM_TYPE']['VALUE'];
        $FORM_TITLE=$props['FORM_TITLE']['VALUE'];
    }
    else{
        $FORM_TYPE=(!empty($arResult['PROPERTIES']['FORM_TYPE']['VALUE'])) ? $arResult['PROPERTIES']['FORM_TYPE']['VALUE'] : "3";
        $FORM_TITLE=$arResult["PROPERTIES"]["TEXT_FORM"]["~VALUE"];
    }

} else {
    $FORM_TYPE=(!empty($arResult['PROPERTIES']['FORM_TYPE']['VALUE'])) ? $arResult['PROPERTIES']['FORM_TYPE']['VALUE'] : "3";
    $FORM_TITLE=$arResult["PROPERTIES"]["TEXT_FORM"]["~VALUE"];
}
$arResult['FORM_TYPE']=$FORM_TYPE;
$arResult['FORM_TITLE']=$FORM_TITLE;

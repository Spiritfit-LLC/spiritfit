<?php
\Bitrix\Main\Loader::includeModule('iblock');

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
    $arResult["PROPERTIES"]["TEAM"]["ITEMS"][] = $item;
}


if (!empty($arResult["PROPERTIES"]["NOT_OPEN_YET"]["VALUE"])) {
    $GLOBALS['arAbonementFilter'] = array('ID' => $arResult['PROPERTIES']['ABONEMENTS']['VALUE']);
} else {
    $GLOBALS['arAbonementFilter'] = array(array(
        'LOGIC' => 'OR',
        array('ID' => $arResult['PROPERTIES']['ABONEMENTS']['VALUE']),
        array('!PROPERTY_HIDDEN' => 40),
    ));
}

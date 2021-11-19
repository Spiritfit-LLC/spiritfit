<?php

foreach($arResult["ITEMS"] as $key => $arItem) {
    $arResult["ITEMS"][$key]["PROPERTIES"]["ICON_MAIN"]["RESIZE"] = CFile::ResizeImageGet($arItem["PROPERTIES"]["ICON_MAIN"]["VALUE"], array("width"=>"200", "height"=>"120", BX_RESIZE_IMAGE_PROPORTIONAL));
}

$dbSections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "NAME", "DESCRIPTION", "UF_LINK_FAQ"));
if ($res = $dbSections->GetNext()) {
    $arResult["SECTION"] = $res;
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
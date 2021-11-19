<?php

$arSecFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"],"ACTIVE" => "Y");
$dbSections = CIBlockSection::GetList(array("SORT"=>"ASC"), $arSecFilter, false, array("ID","NAME","SECTION_PAGE_URL", "UF_*"));
while ($res = $dbSections->GetNext()) {
    if($_REQUEST["SECTION_CODE"] && $_REQUEST["SECTION_CODE"] == $res["CODE"]){
        $arResult["ACTIVE_SECTION"] = $res["ID"];
    }
    $arResult["SECTIONS"][$res["ID"]] = $res;
}

$dbSections = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"],"ACTIVE" => "Y"), false,false, array("ID","NAME","PREVIEW_TEXT","IBLOCK_SECTION_ID"));
while ($res = $dbSections->GetNext()) {
   $arResult["SECTIONS"][$res["IBLOCK_SECTION_ID"]]["ITEMS"][] = $res;
}

foreach ($arResult["SECTIONS"] as $key => $value) {
       $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams["IBLOCK_ID"], $key);
    $seoValues = $ipropValues->getValues();

    if ($seoValues['SECTION_META_TITLE']) {
            $arResult["SECTIONS"][$key]['SEO']['SECTION_META_TITLE'] = $seoValues['SECTION_META_TITLE'];
        }
        if ($seoValues['SECTION_META_DESCRIPTION']) {
            $arResult["SECTIONS"][$key]['SEO']['SECTION_META_DESCRIPTION'] = $seoValues['SECTION_META_DESCRIPTION'];
        }
        if ($seoValues['SECTION_META_KEYWORDS']) {
            $arResult["SECTIONS"][$key]['SEO']['SECTION_META_KEYWORDS'] = $seoValues['SECTION_META_KEYWORDS'];
        }
}